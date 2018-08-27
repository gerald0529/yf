<?php
namespace Yf\Upgrader;
/**
 * Core class used for updating core.
 *
 * It allows for WordPress to upgrade itself in combination with
 * the wp-admin/includes/update-core.php file.
 *
 * @since 2.8.0
 * @since 4.6.0 Moved to its own file from wp-admin/includes/class-wp-upgrader.php.
 *
 * @see Yf\Upgrader\Base
 */
class Core extends Base
{
	
	/**
	 * Initialize the upgrade strings.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function upgrade_strings()
	{
		$this->strings['up_to_date']            = _('WordPress is at the latest version.');
		$this->strings['locked']                = _('Another update is currently in progress.');
		$this->strings['no_package']            = _('Update package not available.');
		$this->strings['downloading_package']   = _('Downloading update from <span class="code">%s</span>&#8230;');
		$this->strings['unpack_package']        = _('Unpacking the update&#8230;');
		$this->strings['copy_failed']           = _('Could not copy files.');
		$this->strings['copy_failed_space']     = _('Could not copy files. You may have run out of disk space.');
		$this->strings['start_rollback']        = _('Attempting to roll back to previous version.');
		$this->strings['rollback_was_required'] = _('Due to an error during updating, WordPress has rolled back to your previous version.');
	}
	
	/**
	 * Upgrade WordPress core.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @global WP_Filesystem_Base $wp_filesystem Subclass
	 * @global callable $_wp_filesystem_direct_method
	 *
	 * @param object $current Response object for whether WordPress is current.
	 * @param array $args {
	 *        Optional. Arguments for upgrading WordPress core. Default empty array.
	 *
	 * @type bool $pre_check_md5 Whether to check the file checksums before
	 *                                     attempting the upgrade. Default true.
	 * @type bool $attempt_rollback Whether to attempt to rollback the chances if
	 *                                     there is a problem. Default false.
	 * @type bool $do_rollback Whether to perform this "upgrade" as a rollback.
	 *                                     Default false.
	 * }
	 * @return null|false|WP_Error False or WP_Error on failure, null on success.
	 */
	public function upgrade($current, $args = array())
	{
		$client_version         = \Web_ConfigModel::value('current_version', '1.0.1');
		$client_db_version      = \Web_ConfigModel::value('current_db_version', '1');
		$required_php_version   = \Web_ConfigModel::value('required_php_version', '5.3');
		$required_mysql_version = \Web_ConfigModel::value('required_mysql_version', '5.0');
		
		$start_time = time();
		
		$defaults = array(
			'pre_check_md5' => true,
			'attempt_rollback' => false,
			'do_rollback' => false,
			'allow_relaxed_file_ownership' => false,
		);
		
		//$parsed_args = wp_parse_args( $args, $defaults );
		$parsed_args = $defaults;
		
		$this->init();
		$this->upgrade_strings();
		
		// Is an update available?
		if (!isset($current->response) || $current->response == 'latest')
		{
			throw new \Exception('up_to_date', $this->strings['up_to_date']);
		}
		
		//检测文件目录
		/*
		$res = $this->fs_connect( array( ABSPATH, WP_CONTENT_DIR ), $parsed_args['allow_relaxed_file_ownership'] );
		if ( ! $res || is_wp_error( $res ) ) {
			return $res;
		}
		*/
		
		//$wp_dir = trailingslashit($wp_filesystem->abspath());
		$wp_dir = ROOT_PATH;
		
		$partial = true;
		if ($parsed_args['do_rollback'])
		{
			$partial = false;
		}
		elseif ($parsed_args['pre_check_md5'] && !$this->checkFiles())
		{
			$partial = false;
		}
		
		/*
		 * If partial update is returned from the API, use that, unless we're doing
		 * a reinstall. If we cross the new_bundled version number, then use
		 * the new_bundled zip. Don't though if the constant is set to skip bundled items.
		 * If the API returns a no_content zip, go with it. Finally, default to the full zip.
		 */
		if ($parsed_args['do_rollback'] && $current->packages->rollback)
		{
			$to_download = 'rollback';
		}
		elseif ($current->packages->partial && 'reinstall' != $current->response && $this->version == $current->partial_version && $partial)
		{
			$to_download = 'partial';
		}
		elseif ($current->packages->new_bundled && version_compare($this->version, $current->new_bundled, '<') && (!defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') || !CORE_UPGRADE_SKIP_NEW_BUNDLED))
		{
			$to_download = 'new_bundled';
		}
		elseif ($current->packages->no_content)
		{
			$to_download = 'no_content';
		}
		else
		{
			$to_download = 'full';
		}
		
		// Lock to prevent multiple Core Updates occurring
		$lock = Base::createLock('core_updater', 15 * 60);
		if (!$lock)
		{
			throw new \Exception('locked', $this->strings['locked']);
		}
		
		update_feedback(_('升级'));
		update_feedback(sprintf('正在从%s下载更新文件… ', $current->packages->$to_download));
		
		
		$download = $this->download_package($current->packages->$to_download);
		
		if (!$download)
		{
			Base::releaseLock('core_updater');
			return $download;
		}
		
		update_feedback(_('正在解压缩升级文件… '));
		
		$working_dir = $this->unpackPackage($download);
		if (!$working_dir)
		{
			Base::releaseLock('core_updater');
			return $working_dir;
		}
		
		
		// Copy update-core.php from the new version into place.
		
		/*
		if ( !copy($working_dir . '/libraries/Yf/Upgrader/Exec.php', $wp_dir . '/libraries/Yf/Upgrader/Exec.php') )
		{
			clean_cache($working_dir, true);
			Base::release_lock( 'core_updater' );
			
			throw new \Exception( 'copy_failed_for_update_core_file');
		}
		*/
		
		/*
		echo "\n";
		echo $working_dir;
		echo "\n";
		echo $wp_dir;
		*/
		
		//$wp_filesystem->chmod($wp_dir . 'wp-admin/includes/update-core.php', FS_CHMOD_FILE);
		$Exec = new Exec($this);
		
		$result = $Exec->updateCore($working_dir, $wp_dir);
		//$result = update_core( $working_dir, $wp_dir );
		
		/*
		// In the event of an issue, we may be able to roll back.
		if ( $parsed_args['attempt_rollback'] && $current->packages->rollback && ! $parsed_args['do_rollback'] ) {
			$try_rollback = false;
			if ( is_wp_error( $result ) ) {
				$error_code = $result->get_error_code();

				if ( false !== strpos( $error_code, 'do_rollback' ) )
					$try_rollback = true;
				elseif ( false !== strpos( $error_code, '__copy_dir' ) )
					$try_rollback = true;
				elseif ( 'disk_full' === $error_code )
					$try_rollback = true;
			}

			if ( $try_rollback ) {
				apply_filters( 'update_feedback', $result );

				apply_filters( 'update_feedback', $this->strings['start_rollback'] );

				$rollback_result = $this->upgrade( $current, array_merge( $parsed_args, array( 'do_rollback' => true ) ) );

				$original_result = $result;
				$result = new WP_Error( 'rollback_was_required', $this->strings['rollback_was_required'], (object) array( 'update' => $original_result, 'rollback' => $rollback_result ) );
			}
		}
		//do_action( 'upgrader_process_complete', $this, array( 'action' => 'update', 'type' => 'core' ) );

		// Clear the current updates
		//delete_site_transient( 'update_core' );

		if ( ! $parsed_args['do_rollback'] ) {
			$stats = array(
				'update_type'      => $current->response,
				'success'          => true,
				'fs_method'        => $wp_filesystem->method,
				'fs_method_forced' => defined( 'FS_METHOD' ) || has_filter( 'filesystem_method' ),
				'fs_method_direct' => !empty( $GLOBALS['_wp_filesystem_direct_method'] ) ? $GLOBALS['_wp_filesystem_direct_method'] : '',
				'time_taken'       => time() - $start_time,
				'reported'         => $this->version,
				'attempted'        => $current->version,
			);

			if ( is_wp_error( $result ) ) {
				$stats['success'] = false;
				// Did a rollback occur?
				if ( ! empty( $try_rollback ) ) {
					$stats['error_code'] = $original_result->get_error_code();
					$stats['error_data'] = $original_result->get_error_data();
					// Was the rollback successful? If not, collect its error too.
					$stats['rollback'] = ! is_wp_error( $rollback_result );
					if ( is_wp_error( $rollback_result ) ) {
						$stats['rollback_code'] = $rollback_result->get_error_code();
						$stats['rollback_data'] = $rollback_result->get_error_data();
					}
				} else {
					$stats['error_code'] = $result->get_error_code();
					$stats['error_data'] = $result->get_error_data();
				}
			}

			wp_version_check( $stats );
		}

		*/
		
		Base::releaseLock('core_updater');
		
		return $result;
	}
	
	/**
	 * Determines if this WordPress Core version should update to an offered version or not.
	 *
	 * @since 3.7.0
	 * @access public
	 *
	 * @static
	 *
	 * @param string $offered_ver The offered version, of the format x.y.z.
	 * @return bool True if we should update to the offered version, otherwise false.
	 */
	public static function should_update_to_version($offered_ver)
	{
		include(ABSPATH . WPINC . '/version.php'); // $this->version; // x.y.z
		
		$current_branch                 = implode('.', array_slice(preg_split('/[.-]/', $this->version), 0, 2)); // x.y
		$new_branch                     = implode('.', array_slice(preg_split('/[.-]/', $offered_ver), 0, 2)); // x.y
		$current_is_development_version = (bool)strpos($this->version, '-');
		
		// Defaults:
		$upgrade_dev   = true;
		$upgrade_minor = true;
		$upgrade_major = false;
		
		// WP_AUTO_UPDATE_CORE = true (all), 'minor', false.
		if (defined('WP_AUTO_UPDATE_CORE'))
		{
			if (false === WP_AUTO_UPDATE_CORE)
			{
				// Defaults to turned off, unless a filter allows it
				$upgrade_dev = $upgrade_minor = $upgrade_major = false;
			}
			elseif (true === WP_AUTO_UPDATE_CORE)
			{
				// ALL updates for core
				$upgrade_dev = $upgrade_minor = $upgrade_major = true;
			}
			elseif ('minor' === WP_AUTO_UPDATE_CORE)
			{
				// Only minor updates for core
				$upgrade_dev   = $upgrade_major = false;
				$upgrade_minor = true;
			}
		}
		
		// 1: If we're already on that version, not much point in updating?
		if ($offered_ver == $this->version)
		{
			return false;
		}
		
		// 2: If we're running a newer version, that's a nope
		if (version_compare($this->version, $offered_ver, '>'))
		{
			return false;
		}
		
		$failure_data = get_site_option('auto_core_update_failed');
		if ($failure_data)
		{
			// If this was a critical update failure, cannot update.
			if (!empty($failure_data['critical']))
			{
				return false;
			}
			
			// Don't claim we can update on update-core.php if we have a non-critical failure logged.
			if ($this->version == $failure_data['current'] && false !== strpos($offered_ver, '.1.next.minor'))
			{
				return false;
			}
			
			// Cannot update if we're retrying the same A to B update that caused a non-critical failure.
			// Some non-critical failures do allow retries, like download_failed.
			// 3.7.1 => 3.7.2 resulted in files_not_writable, if we are still on 3.7.1 and still trying to update to 3.7.2.
			if (empty($failure_data['retry']) && $this->version == $failure_data['current'] && $offered_ver == $failure_data['attempted'])
			{
				return false;
			}
		}
		
		// 3: 3.7-alpha-25000 -> 3.7-alpha-25678 -> 3.7-beta1 -> 3.7-beta2
		if ($current_is_development_version)
		{
			
			/**
			 * Filters whether to enable automatic core updates for development versions.
			 *
			 * @since 3.7.0
			 *
			 * @param bool $upgrade_dev Whether to enable automatic updates for
			 *                          development versions.
			 */
			if (!apply_filters('allow_dev_auto_core_updates', $upgrade_dev))
			{
				return false;
			}
			// Else fall through to minor + major branches below.
		}
		
		// 4: Minor In-branch updates (3.7.0 -> 3.7.1 -> 3.7.2 -> 3.7.4)
		if ($current_branch == $new_branch)
		{
			
			/**
			 * Filters whether to enable minor automatic core updates.
			 *
			 * @since 3.7.0
			 *
			 * @param bool $upgrade_minor Whether to enable minor automatic core updates.
			 */
			return apply_filters('allow_minor_auto_core_updates', $upgrade_minor);
		}
		
		// 5: Major version updates (3.7.0 -> 3.8.0 -> 3.9.1)
		if (version_compare($new_branch, $current_branch, '>'))
		{
			
			/**
			 * Filters whether to enable major automatic core updates.
			 *
			 * @since 3.7.0
			 *
			 * @param bool $upgrade_major Whether to enable major automatic core updates.
			 */
			return apply_filters('allow_major_auto_core_updates', $upgrade_major);
		}
		
		// If we're not sure, we don't want it
		return false;
	}
	
	/**
	 * Compare the disk file checksums against the expected checksums.
	 *
	 * @since 3.7.0
	 * @access public
	 *
	 * @global string $this ->version
	 * @global string $this ->localPackage
	 *
	 * @return bool True if the checksums match, otherwise false.
	 */
	public function checkFiles(&$change_file_row=array())
	{
		$flag = true;
		
		$checksums = $this->getCoreCheckSums($this->version, isset($this->localPackage) ? $this->localPackage : 'zh_CN');
		
		if (!is_array($checksums))
		{
			$flag = false;
			return false;
		}
		
		foreach ($checksums as $file => $checksum)
		{
			// Skip files which get updated
			if ('wp-content' == substr($file, 0, 10))
			{
				continue;
			}
			
			if (!file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . $file) || md5_file(ROOT_PATH . DIRECTORY_SEPARATOR . $file) !== $checksum)
			{
				$change_file_row[] = ROOT_PATH . DIRECTORY_SEPARATOR . $file;
				
				$flag = false;
			}
		}
		
		return $flag;
	}
	
	
	/**
	 * Gets and caches the checksums for the given version of WordPress.
	 *
	 * @since 3.7.0
	 *
	 * @param string $version Version string to query.
	 * @param string $locale Locale to query.
	 * @return bool|array False on failure. An array of checksums on success.
	 */
	public function getCoreCheckSums($version, $locale)
	{
		$app_id = $this->appId;
		
		
		$url = $http_url = 'http://ucenter.yuanfeng021.com/index.php?ctl=Base_AppVersion&met=checkSums&typ=json&' . http_build_query(compact('version', 'locale', 'app_id'), null, '&');
		
		$options = array(
			'timeout' => ((defined('DOING_CRON') && DOING_CRON) ? 30 : 3),
		);
		
		$response = get_url($url, $options, 's');
		
		$response = json_decode($response, true);
		
		if (200 != $response['status'])
		{
			return false;
		}
		
		$body = $response['data'];
		
		if (!is_array($body) || !isset($body['checksums']) || !is_array($body['checksums']))
		{
			return false;
		}
		
		return $body['checksums'];
	}
	
	/**
	 *
	 * @param string $version
	 * @param string $locale
	 * @return object|false
	 */
	public function findCoreUpdate($version, $locale, $from_api)
	{
		
		if (!isset($from_api->updates) || !is_array($from_api->updates))
		{
			return false;
		}
		
		$updates = $from_api->updates;
		foreach ($updates as $update)
		{
			if ($update->current === $version && $update->locale === $locale)
			{
				return $update;
			}
		}
		
		return false;
	}
	
	/**
	 *
	 * @param string $version
	 * @param string $locale
	 * @return object|false
	 */
	public function getCoreUpdateList()
	{
		$client_version = \Web_ConfigModel::value('current_version', '1.0.1');
		$version_rows   = $this->getCoreVersion();
		
		//print_r($version_rows);
		$offers = $version_rows['items'];
		
		foreach ($offers as &$offer)
		{
			foreach ($offer as $offer_key => $value)
			{
				if ('packages' == $offer_key)
				{
					$offer['packages'] = (object)array_intersect_key($offer['packages'], array_fill_keys(array(
																											 'full',
																											 'no_content',
																											 'new_bundled',
																											 'partial',
																											 'rollback'
																										 ), ''));
				}
				elseif ('download' == $offer_key)
				{
					$offer['download'] = $value;
				}
				else
				{
					$offer[$offer_key] = $value;
				}
			}
			$offer = (object)array_intersect_key($offer, array_fill_keys(array(
																			 'response',
																			 'download',
																			 'locale',
																			 'packages',
																			 'current',
																			 'version',
																			 'php_version',
																			 'mysql_version',
																			 'new_bundled',
																			 'partial_version',
																			 'notify_email',
																			 'support_email',
																			 'new_files'
																		 ), ''));
		}
		
		$updates                  = new \stdClass();
		$updates->updates         = $offers;
		$updates->last_checked    = time();
		$updates->version_checked = $client_version;
		
		if (isset($body['translations']))
		{
			$updates->translations = $body['translations'];
		}
		
		return $updates;
	}
	
	
	/**
	 *
	 * @param string $version
	 * @param string $locale
	 * @return object|false
	 */
	public function getCoreVersion()
	{
		
		$version = $this->version;
		$locale  = $this->localPackage;
		
		$Yf_Cache                = \Yf_Cache::create('default');
		$update_core_version_key = sprintf('%s|%s|', \Yf_Registry::get('server_id'), 'update_core_version');
		
		$version_rows = array();
		
		if ($version_rows = $Yf_Cache->get($update_core_version_key))
		{
			
		}
		
		$client_version         = \Web_ConfigModel::value('current_version', '1.0.1');
		$client_db_version      = \Web_ConfigModel::value('current_db_version', '1');
		$required_php_version   = \Web_ConfigModel::value('required_php_version', '5.3');
		$required_mysql_version = \Web_ConfigModel::value('required_mysql_version', '5.0');
		
		if (true || !$version_rows || request_int('force-check'))
		{
			
			$local_package      = LANG; //语言包
			$num_blogs          = 1;
			$user_count         = 1;
			$multisite_enabled  = 1;
			$initial_db_version = 13441;
			$client_version     = $client_version;
			
			$query = array(
				'version' => $client_version,
				'php' => $required_php_version,
				'locale' => 'zh_CN',
				'mysql' => $required_mysql_version,
				'local_package' => isset($local_package) ? $local_package : '',
				'blogs' => $num_blogs,
				'users' => $user_count,
				'multisite_enabled' => $multisite_enabled,
				'app_id' => $this->appId,
				'initial_db_version' => $initial_db_version
			);
			
			$url = sprintf('http://ucenter.yuanfeng021.com/index.php?ctl=Base_AppVersion&met=version&typ=json');
			
			$response = get_url($url, $query, 's');
			$result   = decode_json($response, true);
			
			$version_rows = $result['data'];
			
			$Yf_Cache->save($version_rows, $update_core_version_key);
		}
		//print_r($version_rows);
		
		return $version_rows;
		
	}
}
