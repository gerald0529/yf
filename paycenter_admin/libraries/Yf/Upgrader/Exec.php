<?php
namespace Yf\Upgrader;


use Camcima\MySqlDiff\Differ;
use Camcima\MySqlDiff\Parser;

class Exec
{
	public $version;
	public $localPackage;
	public $appId;
	public $dbId;
	public $handle;
	
	/**
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 */
	public function __construct($handle=null)
	{
		$this->handle = $handle;
		
		$this->appId        = $handle->appId;
		$this->version      = $handle->version;
		$this->localPackage = $handle->localPackage;
		$this->dbId = $handle->dbId;
	}
	
	/**
	 * Upgrades the core of BbcBuilder.
	 *
	 * This will create a .maintenance file at the base of the BbcBuilder directory
	 * to ensure that people can not access the web site, when the files are being
	 * copied to their locations.
	 *
	 * The files in the `$_old_files` list will be removed and the new files
	 * copied from the zip file after the database is upgraded.
	 *
	 * The files in the `$_new_bundled_files` list will be added to the installation
	 * if the version is greater than or equal to the old version being upgraded.
	 *
	 * The steps for the upgrader for after the new release is downloaded and
	 * unzipped is:
	 *   1. Test unzipped location for select files to ensure that unzipped worked.
	 *   2. Create the .maintenance file in current BbcBuilder base.
	 *   3. Copy new BbcBuilder directory over old BbcBuilder files.
	 *   4. Upgrade BbcBuilder to new version.
	 *     4.1. Copy all files/folders other than wp-content
	 *     4.2. Copy any language files to WP_LANG_DIR (which may differ from WP_CONTENT_DIR
	 *     4.3. Copy any new bundled themes/plugins to their respective locations
	 *   5. Delete new BbcBuilder directory path.
	 *   6. Delete .maintenance file.
	 *   7. Remove old files.
	 *   8. Delete 'update_core' option.
	 *
	 * There are several areas of failure. For instance if PHP times out before step
	 * 6, then you will not be able to access any portion of your site. Also, since
	 * the upgrade will not continue where it left off, you will not be able to
	 * automatically remove old files and remove the 'update_core' option. This
	 * isn't that bad.
	 *
	 * If the copy of the new BbcBuilder over the old fails, then the worse is that
	 * the new BbcBuilder directory will remain.
	 *
	 * If it is assumed that every file will be copied over, including plugins and
	 * themes, then if you edit the default theme, you should rename it, so that
	 * your changes remain.
	 *
	 * @since 2.7.0
	 *
	 * @global WP_Filesystem_Base $wp_filesystem
	 * @global array $_old_files
	 * @global array $_new_bundled_files
	 * @global wpdb $wpdb
	 * @global string $client_version
	 * @global string $required_php_version
	 * @global string $required_mysql_version
	 *
	 * @param string $from New release unzipped path.
	 * @param string $to Path to old BbcBuilder installation.
	 * @return WP_Error|null WP_Error on failure, null on success.
	 */
	function updateCore($from, $to)
	{
		global $wp_filesystem, $_old_files, $_new_bundled_files, $wpdb;
		
		@set_time_limit(300);
		
		/**
		 * Filters feedback messages displayed during the core update process.
		 *
		 * The filter is first evaluated after the zip file for the latest version
		 * has been downloaded and unzipped. It is evaluated five more times during
		 * the process:
		 *
		 * 1. Before BbcBuilder begins the core upgrade process.
		 * 2. Before Maintenance Mode is enabled.
		 * 3. Before BbcBuilder begins copying over the necessary files.
		 * 4. Before Maintenance Mode is disabled.
		 * 5. Before the database is upgraded.
		 *
		 * @since 2.5.0
		 *
		 * @param string $feedback The core update feedback messages.
		 */
		//apply_filters( 'update_feedback', _( 'Verifying the unpacked files&#8230;' ) );
		
		// Sanity check the unzipped distribution.
		$distro = '';
		$roots  = array('/' . APP_DIR_NAME);
		foreach ($roots as $root)
		{
			if (\Yf_Utils_File::exists($from . $root . '/configs/version.php'))
			{
				$distro = $root;
				break;
			}
		}
		
		if (!$distro)
		{
			\Yf_Utils_File::cleanDir($from, true);
			throw new \Exception(_('The update could not be unpacked or a wrong install package!'));
		}
		
		
		/**
		 * Import $client_version, $required_php_version, and $required_mysql_version from the new version
		 * $wp_filesystem->wp_content_dir() returned unslashed pre-2.8
		 *
		 * @global string $client_version
		 * @global string $required_php_version
		 * @global string $required_mysql_version
		 */
		$current_version         = \Web_ConfigModel::value('current_version', '1.0.1');
		$current_db_version      = \Web_ConfigModel::value('current_db_version', '1');
		$required_php_version   = \Web_ConfigModel::value('required_php_version', '5.3');
		$required_mysql_version = \Web_ConfigModel::value('required_mysql_version', '5.0');
		
		$versions_file = $from . $distro . '/configs/version-current.php';
		
		if (!\Yf_Utils_File::copy($from . $distro . '/configs/version.php', $versions_file))
		{
			\Yf_Utils_File::delete($from, true);
			throw new \Exception('copy_failed_for_version_file');
		}
		
		chmod($versions_file, 0777);
		
		$old_version = $current_version; // The version of BbcBuilder we're updating from
		
		//读取需要升级的版本
		require($versions_file);
		\Yf_Utils_File::delete($versions_file);
		
		$php_version = phpversion();
		
		$Web_ConfigModel = new \Web_ConfigModel();
		$db              = $Web_ConfigModel->sql->getDb();
		$mysql_version   = $db->version();
		
		
		$development_build = (false !== strpos($old_version . $client_version, '-')); // a dash in the version indicates a Development release
		$php_compat        = version_compare($php_version, $required_php_version, '>=');
		
		/*
		if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) )
			$mysql_compat = true;
		else
			$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );
		*/
		
		$mysql_compat = version_compare($mysql_version, $required_mysql_version, '>=');
		
		if (!$mysql_compat || !$php_compat)
		{
			\Yf_Utils_File::delete($from, true);
		}
		
		if (!$mysql_compat && !$php_compat)
		{
			throw new \Exception(sprintf(_('The update cannot be installed because BbcBuilder %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.'), $client_version, $required_php_version, $required_mysql_version, $php_version, $mysql_version));
		}
		elseif (!$php_compat)
		{
			throw new \Exception(sprintf(_('The update cannot be installed because BbcBuilder %1$s requires PHP version %2$s or higher. You are running version %3$s.'), $client_version, $required_php_version, $php_version));
		}
		elseif (!$mysql_compat)
		{
			throw new \Exception(sprintf(_('The update cannot be installed because BbcBuilder %1$s requires MySQL version %2$s or higher. You are running version %3$s.'), $client_version, $required_mysql_version, $mysql_version));
		}
		
		/** This filter is documented in wp-admin/includes/update-core.php */
		update_feedback(_('正在准备安装最新版本…&#8230;'));
		
		// Don't copy wp-content, we'll deal with that below
		// We also copy version.php last so failed updates report their old version
		$skip              = array(
			ROOT_PATH . DIRECTORY_SEPARATOR . APP_DIR_NAME . '/configs/version.php',
			ROOT_PATH . DIRECTORY_SEPARATOR . 'db.sql',
			ROOT_PATH . DIRECTORY_SEPARATOR . 'shop/configs/version.php',
			ROOT_PATH . DIRECTORY_SEPARATOR . 'pay/configs/version.php'
		);
		$check_is_writable = array();
		
		update_feedback(_('正在检测文件内容… '));
		// Check to see which files don't really need updating - only available for 3.7 and higher
		if (true)
		{
			// Find the local version of the working directory
			$working_dir_local = $from;
			
			$upgrader  = $this->handle;
			$checksums = $upgrader->getCoreCheckSums($client_version, isset($wp_local_package) ? $wp_local_package : 'zh_CN');
			
			
			if (is_array($checksums))
			{
				foreach ($checksums as $file => $checksum)
				{
					if ('wp-content' == substr($file, 0, 10))
					{
						continue;
					}
					
					if (!file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . $file))
					{
						continue;
					}
					
					if (!file_exists($working_dir_local . DIRECTORY_SEPARATOR . $file))
					{
						continue;
					}
					
					
					if (md5_file(ROOT_PATH . DIRECTORY_SEPARATOR . $file) === $checksum)
					{
						$skip[] = ROOT_PATH . DIRECTORY_SEPARATOR . $file;
					}
					else
					{
						$check_is_writable[$file] = ROOT_PATH . DIRECTORY_SEPARATOR . $file;
					}
				}
			}
		}
		
		update_feedback(_( '判断是否是否可写...' ));
		//$check_is_writable
		
		// If we're using the direct method, we can predict write failures that are due to permissions.
		if ($check_is_writable)
		{
			$files_writable = array_filter($check_is_writable, 'is_writable');
			
			if ($files_writable !== $check_is_writable)
			{
				$files_not_writable = array_diff_key($check_is_writable, $files_writable);
				
				foreach ($files_not_writable as $relative_file_not_writable => $file_not_writable)
				{
					// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
					chmod($file_not_writable, 0644);
					if (is_writable($file_not_writable))
					{
						unset($files_not_writable[$relative_file_not_writable]);
					}
				}
				
				// Store package-relative paths (the key) of non-writable files in the WP_Error object.
				$error_data = array_keys($files_not_writable);
				
				if ($files_not_writable)
				{
					throw new \Exception(_('The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.'));
				}
			}
		}
		
		
		update_feedback(_('正在启用维护模式… '));
		
		// Create maintenance file to signal that we are upgrading
		$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
		$maintenance_file   = $to . '.maintenance';
		\Yf_Utils_File::delete($maintenance_file);
		file_put_contents($maintenance_file, $maintenance_string);
		
		update_feedback(_('正在复制所需的文件… '));
		
		// Copy new versions of WP files into place.
		//$result = \Yf_Utils_File::copyDir( $from . $distro, $to, $skip );
		$result = \Yf_Utils_File::copyDir($from, $to, $skip);
		
		
		// Since we know the core files have copied over, we can now copy the version file
		if ($result)
		{
			if (!\Yf_Utils_File::copy($from . $distro . '/configs/version.php', $to . $distro . '/configs/version.php'))
			{
				\Yf_Utils_File::delete($from, true);
				throw new \Exception(sprintf(_('The update cannot be installed because we will be unable to copy some files: "%s" . This is usually due to inconsistent file permissions.'), $to . $distro . '/configs/version.php'));
			}
			
			//chmod( $to . 'shop_admin/configs/version.php', 0777 );
		}
		
		// Check to make sure everything copied correctly, ignoring the contents of wp-content
		$skip   = array('wp-content');
		$failed = array();
		if (isset($checksums) && is_array($checksums))
		{
			foreach ($checksums as $file => $checksum)
			{
				if ('wp-content' == substr($file, 0, 10))
				{
					continue;
				}
				if (!file_exists($working_dir_local . DIRECTORY_SEPARATOR . $file))
				{
					continue;
				}
				if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . $file) && md5_file(ROOT_PATH . DIRECTORY_SEPARATOR . $file) == $checksum)
				{
					$skip[] = $file;
				}
				else
				{
					$failed[] = $file;
				}
			}
		}
		
		
		// Some files didn't copy properly
		if (!empty($failed))
		{
			$total_size = 0;
			foreach ($failed as $file)
			{
				if (file_exists($working_dir_local . DIRECTORY_SEPARATOR . $file))
				{
					$total_size += filesize($working_dir_local . DIRECTORY_SEPARATOR . $file);
				}
			}
			
			// If we don't have enough free space, it isn't worth trying again.
			// Unlikely to be hit due to the check in unzip_file().
			$available_space = @disk_free_space(ROOT_PATH);
			if ($available_space && $total_size >= $available_space)
			{
				throw new \Exception(_('There is not enough free disk space to complete the update.'));
			}
			else
			{
				$result = \Yf_Utils_File::copyDir($from, $to, $skip);
				if (!$result)
				{
					throw new \Exception(_('再次复制文件失败.'));
				}
			}
		}
		
		/*
		// Custom Content Directory needs updating now.
		// Copy Languages
		if ( !is_wp_error($result) && $wp_filesystem->is_dir($from . 'wp-content/languages') ) {
			if ( WP_LANG_DIR != ROOT_PATH . WPINC . '/languages' || @is_dir(WP_LANG_DIR) )
				$lang_dir = WP_LANG_DIR;
			else
				$lang_dir = WP_CONTENT_DIR . '/languages';
			
			if ( !@is_dir($lang_dir) && 0 === strpos($lang_dir, ROOT_PATH) ) { // Check the language directory exists first
				$wp_filesystem->mkdir($to . str_replace(ROOT_PATH, '', $lang_dir), FS_CHMOD_DIR); // If it's within the ROOT_PATH we can handle it here, otherwise they're out of luck.
				clearstatcache(); // for FTP, Need to clear the stat cache
			}
			
			if ( @is_dir($lang_dir) ) {
				$wp_lang_dir = $wp_filesystem->find_folder($lang_dir);
				if ( $wp_lang_dir ) {
					$result = copy_dir($from . 'wp-content/languages/', $wp_lang_dir);
					if ( is_wp_error( $result ) )
						$result = new WP_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $wp_lang_dir ) ) );
				}
			}
		}
		*/
		// Remove maintenance file, we're done with potential site-breaking changes
		\Yf_Utils_File::delete($maintenance_file);
		
		/*
		 $_new_bundled_files = array(
			'plugins/akismet/'       => '2.0',
			'themes/twentyten/'      => '3.0',
			'themes/twentyeleven/'   => '3.2',
			'themes/twentytwelve/'   => '3.5',
			'themes/twentythirteen/' => '3.6',
			'themes/twentyfourteen/' => '3.8',
			'themes/twentyfifteen/'  => '4.1',
			'themes/twentysixteen/'  => '4.4',
		);

		// Copy New bundled plugins & themes
		// This gives us the ability to install new plugins & themes bundled with future versions of BbcBuilder whilst avoiding the re-install upon upgrade issue.
		// $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated
		if ( !is_wp_error($result) && ( ! defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
			foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
				// If a $development_build or if $introduced version is greater than what the site was previously running
				if ( $development_build || version_compare( $introduced_version, $old_version, '>' ) ) {
					$directory = ('/' == $file[ strlen($file)-1 ]);
					list($type, $filename) = explode('/', $file, 2);
					
					// Check to see if the bundled items exist before attempting to copy them
					if ( ! $wp_filesystem->exists( $from . 'wp-content/' . $file ) )
						continue;
					
					if ( 'plugins' == $type )
						$dest = $wp_filesystem->wp_plugins_dir();
					elseif ( 'themes' == $type )
						$dest = trailingslashit($wp_filesystem->wp_themes_dir()); // Back-compat, ::wp_themes_dir() did not return trailingslash'd pre-3.2
					else
						continue;
					
					if ( ! $directory ) {
						if ( ! $development_build && $wp_filesystem->exists( $dest . $filename ) )
							continue;
						
						if ( ! $wp_filesystem->copy($from . 'wp-content/' . $file, $dest . $filename, FS_CHMOD_FILE) )
							$result = new WP_Error( "copy_failed_for_new_bundled_$type", _( 'Could not copy file.' ), $dest . $filename );
					} else {
						if ( ! $development_build && $wp_filesystem->is_dir( $dest . $filename ) )
							continue;
						
						$wp_filesystem->mkdir($dest . $filename, FS_CHMOD_DIR);
						$_result = copy_dir( $from . 'wp-content/' . $file, $dest . $filename);
						
						// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
						if ( is_wp_error( $_result ) ) {
							if ( ! is_wp_error( $result ) )
								$result = new WP_Error;
							$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
						}
					}
				}
			} //end foreach
		}
		
		// Handle $result error from the above blocks
		if ( is_wp_error($result) ) {
			$wp_filesystem->delete($from, true);
			return $result;
		}
		
		// Remove old files
		foreach ( $_old_files as $old_file ) {
			$old_file = $to . $old_file;
			if ( !$wp_filesystem->exists($old_file) )
				continue;
			$wp_filesystem->delete($old_file, true);
		}
		*/
		// Upgrade DB with separate request
		
		update_feedback(_('正在升级数据库… '));
		
		/*
		$db_upgrade_url = admin_url('upgrade.php?step=upgrade_db');
		wp_remote_post($db_upgrade_url, array('timeout' => 60));
		
		// Clear the cache to prevent an update_option() from saving a stale db_version to the cache
		wp_cache_flush();
		// (Not all cache back ends listen to 'flush')
		wp_cache_delete( 'alloptions', 'options' );
		
		// Remove working directory
		$wp_filesystem->delete($from, true);
		
		// Force refresh of update information
		if ( function_exists('delete_site_transient') )
			delete_site_transient('update_core');
		else
			delete_option('update_core');

		//do_action( '_core_updated_successfully', $client_version );
		*/
		
		// We are up-to-date. Nothing to do.
		if ($client_db_version == $current_db_version)
		{
			update_feedback(_('重新初始化数据库… '));
			//return;
		}
		
		
		if (version_compare($mysql_version, $required_mysql_version, '<'))
		{
			throw new \Exception(sprintf(_('<strong>ERROR</strong>: WordPress %1$s requires MySQL %2$s or higher'), $client_db_version, $required_mysql_version));
		}
		
		//清除全部数据cache
		$error_row = array();
		$data_row  = array();
		
		$config_cache = \Yf_Registry::get('config_cache');
		
		foreach ($config_cache as $name => $item)
		{
			if (isset($item['cacheDir']))
			{
				if (clean_cache($item['cacheDir']))
				{
					$data_row[] = $item['cacheDir'];
				}
				else
				{
					$error_row[] = $item['cacheDir'];
				}
				
				$Cache = \Yf_Cache::create($name);
				
				$data_row[] = json_encode(@$config_cache['memcache'][$name]);
				
				if (method_exists($Cache, 'flush') && !$Cache->flush())
				{
					$error_row[] = 'memcache-' . $name;
				}
			}
			else
			{
				
			}
		}
		
		
		//print_r($rs);
		
		$this->preSchemaUpgrade();
		
		//$from_s = $from . $distro . '/db.sql';
		$from_s = $from . '/db.sql';
	
		if (file_exists($from_s) &&  $from_db_str = file_get_contents($from_s))
		{
			$flag   = $this->makeDbCurrentSilent($from_db_str);
		}
		
		/*
		upgrade_all();
		if ( is_multisite() && is_main_site() )
			upgrade_network();
		wp_cache_flush();
		
		if ( is_multisite() ) {
			if ( $wpdb->get_row( "SELECT blog_id FROM {$wpdb->blog_versions} WHERE blog_id = '{$wpdb->blogid}'" ) )
				$wpdb->query( "UPDATE {$wpdb->blog_versions} SET db_version = '{$wp_db_version}' WHERE blog_id = '{$wpdb->blogid}'" );
			else
				$wpdb->query( "INSERT INTO {$wpdb->blog_versions} ( `blog_id` , `db_version` , `last_updated` ) VALUES ( '{$wpdb->blogid}', '{$wp_db_version}', NOW());" );
		}
		
		*/
		
		
		//更新当前版本号
		if ($Web_ConfigModel->getConfig('current_version'))
		{
			$Web_ConfigModel->editConfig('current_version', array('config_value' => $client_version));
		}
		else
		{
			$Web_ConfigModel->addConfig(array('config_key' => 'current_version', 'config_value' => $client_version, 'config_type'=>'site', 'config_comment'=>''));
		}
		
		if ($Web_ConfigModel->getConfig('current_db_version'))
		{
			$Web_ConfigModel->editConfig('current_db_version', array('config_value' => $client_db_version));
		}
		else
		{
			$Web_ConfigModel->addConfig(array('config_key' => 'current_db_version', 'config_value' => $client_db_version, 'config_type'=>'site', 'config_comment'=>''));
		}
		
		
		if ($Web_ConfigModel->getConfig('required_php_version'))
		{
			$Web_ConfigModel->editConfig('required_php_version', array('config_value' => $required_php_version));
		}
		else
		{
			$Web_ConfigModel->addConfig(array('config_key' => 'required_php_version', 'config_value' => $required_php_version, 'config_type'=>'site', 'config_comment'=>''));
		}
		
		
		if ($Web_ConfigModel->getConfig('required_mysql_version'))
		{
			$Web_ConfigModel->editConfig('required_mysql_version', array('config_value' => $required_mysql_version));
		}
		else
		{
			$Web_ConfigModel->addConfig(array('config_key' => 'required_mysql_version', 'config_value' => $required_mysql_version, 'config_type'=>'site', 'config_comment'=>''));
		}
		
		
		update_feedback(_('升级完成… '));
		
		return $client_version;
	}
	
	public function preSchemaUpgrade()
	{
		/*
		// Upgrade versions prior to 4.2.
		if ( $wp_current_db_version < 31351 ) {
			if ( ! is_multisite() && wp_should_upgrade_global_tables() ) {
				$wpdb->query( "ALTER TABLE $wpdb->usermeta DROP INDEX meta_key, ADD INDEX meta_key(meta_key(191))" );
			}
			$wpdb->query( "ALTER TABLE $wpdb->terms DROP INDEX slug, ADD INDEX slug(slug(191))" );
			$wpdb->query( "ALTER TABLE $wpdb->terms DROP INDEX name, ADD INDEX name(name(191))" );
			$wpdb->query( "ALTER TABLE $wpdb->commentmeta DROP INDEX meta_key, ADD INDEX meta_key(meta_key(191))" );
			$wpdb->query( "ALTER TABLE $wpdb->postmeta DROP INDEX meta_key, ADD INDEX meta_key(meta_key(191))" );
			$wpdb->query( "ALTER TABLE $wpdb->posts DROP INDEX post_name, ADD INDEX post_name(post_name(191))" );
		}
		*/
	}
	
	public function makeDbCurrentSilent($structure_from)
	{
		$glue = ';' . PHP_EOL . '/* */;' . PHP_EOL;
		
		/*
		$config = Yf_Registry::get('db_cfg');
		
		foreach ($config['db_cfg_rows']['master'] as $db_id)
		{
			
		}
		*/
		
		$db_id = $this->dbId;
		
		$db_to          = \Yf_Db::get($db_id);
		$structure_rows = $db_to->getStructure();
		$structure_to   = implode($glue, $structure_rows) . $glue;
		
		//echo $structure_from;
		//echo $structure_to;
		
		
		$parser = new Parser();
		
		$from_db = $parser->parseDatabase($structure_from);
		$to_db   = $parser->parseDatabase($structure_to);
		
		$differ       = new Differ();
		$databaseDiff = $differ->diffDatabases($to_db, $from_db);
		
		
		if (!$databaseDiff->isEmptyDifferences())
		{
			
			//删除掉DROP操作，降低数据风险？
			$result      = $differ->generateMigrationScript($databaseDiff);
			$db_log_file = APP_PATH . '/data/logs/' . get_date_time() . '_' . uniqid();
			
			$flag = file_put_contents($db_log_file, $result);
			
			if ($flag)
			{
				$DbManage = new \Yf_Utils_DbManage($db_to, $this->handle->dbPrefix, $this->handle->dbPrefixBase, false);
				$flag     = $DbManage->import($db_log_file);
			}
			
			if (!$flag)
			{
				update_feedback(_('升级数据库失败… '));
				update_feedback(print_r($DbManage->msg));
			}
			
			return $flag;
		}
	}
	
}
