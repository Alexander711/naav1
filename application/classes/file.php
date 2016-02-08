<?php
defined('SYSPATH') or die('No direct script access.');
class File extends Kohana_File
{
    /**
   	 * Lookup file extensions by MIME type
   	 *
   	 * @param   string  $type File MIME type
   	 * @return  array   File extensions matching MIME type
   	 */
   	public static function exts_by_mime($type)
   	{
   		static $types = array();

   		// Fill the static array
   		if (empty($types))
   		{
   			foreach (Kohana::$config->load('mimes_fix') as $ext => $mimes)
   			{
   				foreach ($mimes as $mime)
   				{
   					if ($mime == 'application/octet-stream')
   					{
   						// octet-stream is a generic binary
   						continue;
   					}

   					if ( ! isset($types[$mime]))
   					{
   						$types[$mime] = array( (string) $ext);
   					}
   					elseif ( ! in_array($ext, $types[$mime]))
   					{
   						$types[$mime][] = (string) $ext;
   					}
   				}
   			}
   		}
   		return isset($types[$type]) ? $types[$type] : FALSE;
   	}
    /**
   	 * Lookup a single file extension by MIME type.
   	 *
   	 * @param   string  $type  MIME type to lookup
   	 * @return  mixed          First file extension matching or false
   	 */
   	public static function ext_by_mime($type)
   	{
   		return current(File::exts_by_mime($type));
   	}
}