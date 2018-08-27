<?php

class Yf_Utils_String
{
	public static function isEmail($email)
	{
		/*
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if (preg_match($pattern, $email_address))
		{
			$user_name = preg_replace( $pattern ,"$1", $email_address );
			$domain_name = preg_replace( $pattern ,"$2", $email_address );
		}
		else
		{
		}
		*/

		//匹配包括._-在内的各种邮箱
		//$email = 'love.you-2013_mark@siteuurl.com.cn.gd.fuck';

		//$regex = '/^[\w\d][0-9a-z-._]+@{1}([0-9a-z][0-9a-z-]+.)+[a-z]{2,4}$/i';
		$regex = '/^[0-9a-z][0-9a-z-._]+@{1}[0-9a-z.-]+[a-z]{2,4}$/i';

		if (preg_match($regex, $email, $match))
		{
			return true;
		}

		return false;
	}

	/**
	 * 判断是否手机号码
	 *
	 */
	public static function isMobile($str)
	{
		return ( ! preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $str) )?FALSE:TRUE;
	}


	/**
	 * Replaces double line-breaks with paragraph elements.
	 *
	 * A group of regex replaces used to identify text formatted with newlines and
	 * replace double line-breaks with HTML paragraph tags. The remaining
	 * line-breaks after conversion become <<br />> tags, unless $br is set to '0'
	 * or 'false'.
	 *
	 * @since 0.71
	 *
	 * @param string $pee The text which has to be formatted.
	 * @param bool $br Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
	 * @return string Text which has been converted into correct paragraph tags.
	 */

	public static function autoP($pee, $br = true)
	{
		$pre_tags = array();

		if (trim($pee) === '')
		{
			return '';
		}

		$pee = $pee . "\n"; // just to make things a little easier, pad the end

		if (strpos($pee, '<pre') !== false)
		{
			$pee_parts = explode('</pre>', $pee);
			$last_pee  = array_pop($pee_parts);
			$pee       = '';
			$i         = 0;

			foreach ($pee_parts as $pee_part)
			{
				$start = strpos($pee_part, '<pre');

				// Malformed html?
				if ($start === false)
				{
					$pee .= $pee_part;
					continue;
				}

				$name            = "<pre wp-pre-tag-$i></pre>";
				$pre_tags[$name] = substr($pee_part, $start) . '</pre>';

				$pee .= substr($pee_part, 0, $start) . $name;
				$i++;
			}

			$pee .= $last_pee;
		}

		$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
		// Space things out a little
		$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|details|menu|summary)';
		$pee       = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
		$pee       = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
		$pee       = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines

		if (strpos($pee, '<option') !== false)
		{
			// no P/BR around option
			$pee = preg_replace('|\s*<option|', '<option', $pee);
			$pee = preg_replace('|</option>\s*|', '</option>', $pee);
		}

		if (strpos($pee, '</object>') !== false)
		{
			// no P/BR around param and embed
			$pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
			$pee = preg_replace('|\s*</object>|', '</object>', $pee);
			$pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
		}

		if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false)
		{
			// no P/BR around source and track
			$pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
			$pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
			$pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
		}

		$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
		// make paragraphs, including one at the end
		$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
		$pee  = '';

		foreach ($pees as $tinkle)
		{
			$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
		}

		$pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
		$pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
		$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
		$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
		$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
		$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

		if ($br)
		{
			//$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);
			$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
			$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
		}

		$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
		$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
		$pee = preg_replace("|\n</p>$|", '</p>', $pee);

		if (!empty($pre_tags))
		{
			$pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
		}

		return $pee;
	}

	public static function url($host, $query_str='', $query_row=array())
	{
		$query_str_row = array();
		parse_str($query_str, $query_str_row);

		$query_row = $query_str_row + $query_row;

		$url = $host;

		if ($query_row)
		{
			ksort($query_row);

			$url_query = http_build_query($query_row);

			$url = $host . '?' . $url_query;
		}

		return $url;
	}

	//获得密码单向加密字符串
	public static function getPasswd($str)
	{
		$res = array();
		if(is_array($str) || empty($str))
		{
			$res['no'] = 1;
			$res['str'] = false;
			$res['msg'] = '输入字符为空，不合法';
			return $res;
		}
		if(strlen($str) >16)
		{
			$res['no'] = 2;
			$res['str'] = false;
			$res['msg'] = '输入字符超过字符限制';
			return $res;
		}
		$str = md5('erpb' . md5($str));
		$res['no'] = 0;
		$res['str'] = $str;
		$res['msg'] = '成功';
		return $res;
	}
}
?>