<?php
/**
 * 内存共享
 *
 * 内存共享，尚未加入信号量
 *
 * @category   Framework
 * @package    Cache
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 *
 */
class Shm
{
    var $shmId;
    var $shmMod;
    
    function Shm ()
    {
        // Init Shared Memory Support...
        // Both SysV Shm and Shmop are support under *NIX Operating System
        // But Only Shmop can be used in Windows.
        
        if (get_sys() == SYSTEM_WIN)
        {
            if (function_exists ('shmop_open'))
            {
                $this->shmMod = 'shmop';
            }
            else
            {
                $this->shmMod = 'none';
                $this->shmId = false;
            }
        }
        else 
        {
            //Support for this functions are not enabled by default. To enable System V semaphore support compile PHP with the option --enable-sysvsem. To enable the System V shared memory support compile PHP with the option --enable-sysvshm. To enable the System V messages support compile PHP with the option --enable-sysvmsg. 
            if (function_exists ('shm_attach'))
            {
                $this->shmMod = 'sysv';
            }
            elseif (function_exists ('shmop_open'))
            {
                //To use shmop you will need to compile PHP with the --enable-shmop parameter in your configure line. 
                $this->shmMod = 'shmop';
            }
            else
            {
                // No Module installed
                $this->shmMod = 'none';
                $this->shmId = false;
            }
        }
        
        if ('sysv' == $this->shmMod) 
        {
            $this->shmId = shm_attach(ftok (__FILE__, 't'), SHM_SIZE, 0644);
        }
        elseif ('shmop' == $this->shmMod) 
        {
            // if no "sysv" module installed, function "ftok())" is unavailiable.
            if (function_exists('ftok'))
            {
                $this->shmId = shmop_open(ftok (__FILE__, 't'), 'c', 0644, SHM_SIZE);
            }
            else
            {
                $this->shmId = shmop_open(SHM_KEY, 'c', 0644, SHM_SIZE);
            }
        }
        
        return;
    }
    
    function set($key, $value)
    {
        // Write a value into shm
        if ('sysv' == $this->shmMod)
        {
            return shm_put_var($this->shmId, $key, $value);
        }
        elseif ('shmop' == $this->shmMod)
        {
            // shmop is much more low-level than sysv, you need to operate every byte yourself!
            $curr = shmop_read($this->shmId, 0, shmop_size($this->shmId));

            /*
            $curr = base64_decode ($curr);
            $curr = substr ($curr, 0, strpos ($curr, "\0"));
            $curr = base64_encode (serialize ($curr)) . "\0";
            */

            $i = strpos($value, "\0");

            if (false === $i) 
            {
            }
            else
            {
                $curr =  substr($curr, 0, $i);
            }

            $curr = $curr ? unserialize($curr) : array();
            
            $curr[$key] = $value;
            $curr = serialize($curr) . "\0";
            
            return shmop_write($this->shmId, $curr, 0);
        }
        else
        {
            return false;
        }
    }
    
    function get($key)
    {
        // Fetch a value by a given key
        if ('sysv' == $this->shmMod)
        {
            return shm_get_var($this->shmId, $key);
        }
        elseif ('shmop' == $this->shmMod)
        {
            $curr = shmop_read ($this->shmId, 0, shmop_size($this->shmId));

            $i = strpos($value, "\0");

            if (false === $i) 
            {
            }
            else
            {
                $curr =  substr($curr, 0, $i);
            }

            $curr = $curr ? unserialize($curr) : array ();
            
            return $curr[$key];
        }
        else
        {
            return false;
        }
    }
    
    function remove($key)
    {
        // Remove a value from shm
        if ('sysv' == $this->shmMod)
        {
            return @shm_remove_var ($this->shmId, $key);
        }        
        elseif ('shmop' == $this->shmMod) 
        {
            $curr = shmop_read ($this->shmId, 0, shmop_size ($this->shmId));

            $i = strpos($value, "\0");

            if (false === $i) 
            {
            }
            else
            {
                $curr =  substr($curr, 0, $i);
            }

            $curr = $curr ? unserialize ($curr) : array ();
            
            unset ($curr[$key]);

            $curr = serialize($curr) . "\0";
            
            return shmop_write ($this->shmId, $curr, 0);
        }
        
        else
            return false;
    }
    
    function detach ()
    {
        // disconnect/close a shm
        if ('sysv' == $this->shmMod)
            return shm_detach ($this->shmId);
        
        elseif ('shmop' == $this->shmMod)
            return shmop_close ($this->shmId);
        
        else
            return false;
    }
    
    function destroy ()
    {
        // Bye...
        if ('sysv' == $this->shmMod)
            return shm_remove ($this->shmId);
        
        elseif ('shmop' == $this->shmMod)
            return shmop_delete ($this->shmId);
        
        else
            return false;
    }
}
?>