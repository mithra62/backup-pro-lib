<?php
namespace mithra62\BackupPro\Db;


class AbstractDb
{
    public $dbhost = 'localhost';            // default database host
    public $dblogin;                         // database login name
    public $dbpass;                          // database login password
    public $dbport = '' ;                    // default port to connect to the database server.
    public $dbname;                          // database name
    public $dblink;                          // database link identifier

    /**
     * @access private
     * @var resource The query id of the last query.
     */

    public $queryid;                         // database query identifier
    public $error = array();                 // storage for error messages
    public $record = array();                // database query record identifier
    public $totalrecords;                    // the total number of records received from a select statement
    public $last_insert_id;                  // last incremented value of the primary key
    public $previd = 0;                      // previus record id. [for navigating through the db]
    public $transactions_capable = false;    // does the server support transactions?
    public $begin_work = false;              // sentinel to keep track of active transactions
    public $lastQuery ;
    public $debug = false ;
    public $_affectedRows ;                  // Number of rows affected by the last query.
    
    public function getDbHost()
    {
        return $this->dbhost ;
    } // end function

    /**
     * @desc return the current database connection resource.
     * @returns the current database connection resource.
     * @access public
     */

    public function getDbLink()
    {
        return $this->dblink;
    } // end function

    public function getDbLogin()
    {
        return $this->dblogin;
    } // end function

    public function getDbPass()
    {
        return $this->dbpass;

    } // end function

    /**
     * @desc Return the optional port portion of the dbhost.
     * @returns The port or the null string if there was no port specified
     * @access
     */

    public function get_dbport()
    {
        return $this->dbport ;
    } // end function

    public function getDbName()
    {
        return $this->dbname;

    } // end function

    public function setDbHost($value)
    {
        if ($value === NULL)
        {
            $value = 'localhost' ;
        }
        return $this->dbhost = $value;

    } // end function

    public function setDbPort($value)
    {
        if ($value === NULL)
        {
            $value = '' ;
        }
        return $this->dbport = $value;

    } // end function

    public function setDbLogin($value)
    {
        return $this->dblogin = $value;

    } // end function

    public function setDbPass($value)
    {
        return $this->dbpass = $value;

    } // end function

    public function setDbName($value)
    {
        return $this->dbname = $value;

    } // end function

    public function getErrors()
    {
        return $this->error;

    } // end function

    /**
     * End of the Get and Set methods
     */

    /**
     * Constructor
     *
     * @param      String $dblogin, String $dbpass, String $dbname
     * @return     void
     * @access     public
     */

    public function __construct($dblogin, $dbpass, $dbname, $dbhost = null)
    {
        $this->setDbLogin($dblogin);
        $this->setDbPass($dbpass);
        $this->setDbName($dbname);

        if ($dbhost != null)
        {
            $xxx = explode(':', $dbhost) ;
            $this->setDbHost($xxx[0]) ;
            $this->setDbPort((array_key_exists(1, $xxx) ? $xxx[1] : '')) ;
        }

    } // end function

    /**
     * Connect to the database and change to the appropriate database.
     *
     * @param      none
     * @return     database link identifier
     * @access     public
     * @scope      public
     */

    public function connect()
    {
        $this->dblink = $this->dBConnect($this->dbhost, $this->dblogin, $this->dbpass, $this->dbname);

        if (!$this->dblink) {
            $this->returnError('Unable to connect to the database.');
        }
        
        return $this->dblink;

    } // end function

    /**
     * Disconnect from the database.
     *
     * A side effect has been introduced that clears the last result on disconnect.
     * This is to attempt to better manage memory allocation.
     *
     * @param      none
     * @return     void
     * @access     public
     * @scope      public
     */
    public function disconnect()
    {
        $this->clear() ;
        $test = @$this->db_close($this->dblink);

        if (!$test) {
            $this->returnError('Unable to close the connection.');
        }

        unset($this->dblink);

    } // end function

    public function escapeString($theString)
    {
        if (empty($this->dblink)) {
            // check to see if there is an open connection. If not, create one.
            $this->connect();
        }

        return $this->db_real_escape_string($theString, $this->dblink) ;
    }

    /**
     * Stores error messages
     *
     * @param      String $message
     * @return     String
     * @access     private
     * @scope      public
     */
    public function returnError($message)
    {
        if ($this->debug)
        {
            return $this->error[] = $message.'<pre>'.var_export(debug_backtrace()).'</pre>'.$this->db_error().'.';
        }
        else
        {
            return $this->error[] = $message . $this->db_error() . '.';
        }
    } // end function

    /**
     * Show any errors that occurred.
     *
     * @param      boolean $theTextFlag [optional] If true, the error text is returned
     *                     as a string.
     * @return     mixed When $theTextFlag is true, return a string.
     * @access     public
     * @scope      public
     */
    public function showErrors($theTextFlag = false)
    {
        if ($this->hasErrors()) {
            reset($this->error);

            $errcount = count($this->error);    //count the number of error messages

            $theErrorText = "<p>Error(s) found: <b>'$errcount'</b></p>\n";

            // print all the error messages.
            while (list($key, $val) = each($this->error)) {
                $theErrorText .= "+ $val<br>\n";
            }

            $this->resetErrors();

            if ($theTextFlag) {
                return $theErrorText ;
            }
            else {
                echo $theErrorText ;
            }
        }

    } // end function

    /**
     * Checks to see if there are any error messages that have been reported.
     *
     * @param      none
     * @return     boolean
     * @access     private
     */
    public function hasErrors()
    {
        if (count($this->error) > 0) {
            return true;
        } else {
            return false;
        }

    } // end function

    /**
     * Clears all the error messages.
     *
     * @param      none
     * @return     void
     * @access     public
     */
    public function resetErrors()
    {
        if ($this->hasErrors()) {
            unset($this->error);
            $this->error = array();
        }

    } // end function

    /**
     * Performs an SQL query whose argument is a constant.
     *
     * @param      String $sql
     * @return     resource query identifier
     * @access     public
     */

    public function queryConstant($sql)
    {
        return $this->query($sql) ;
    }

    /**
     * Performs an SQL query.
     *
     * @param      String $sql
     * @return     resource query identifier
     * @access     public
     */

    public function query(&$sql)
    {
        $this->lastQuery = $sql ;
        if (empty($this->dblink)) {
            // check to see if there is an open connection. If not, create one.
            $this->connect();
        }
        $this->queryid = $this->dbQuery($sql, $this->dblink);

        if ($this->queryid)
        {
            $this->_affectedRows = $this->affectedRows($this->dblink) ;
        }
        else
        {
            if ($this->begin_work)
            {
                $this->rollbackTransaction();
            }
            $this->returnError('Unable to perform the query <b>' . $sql . '</b>.');
        }

        $this->previd = 0;

        return $this->queryid;

    } // end function

    /**
     * Grabs the records as an indexed array.
     *
     * As a side effect, it keeps track of the record's position within the
     * result.
     *
     * @access     public
     * @param      resource [optional] The result from which data is to be fetched.
     * @return     mixed an array containing a DB record.
     */

    public function fetchRow($theQueryId = NULL)
    {
        if (($theQueryId !== NULL) && ($theQueryId != $this->queryid))
        {
            return @$this->db_fetch_array($theQueryId) ;
        }
        else if (isset($this->queryid))
        {
            $this->previd++;
            return $this->record = @$this->db_fetch_array($this->queryid);
        }
        else
        {
            $this->returnError('No query specified.');
        }

    }

    /**
     * Grabs the records as an associative array.
     *
     * As a side effect, it keeps track of the record's position within the
     * result.
     *
     * Multiple results can be active for the span of a single object so it is allowed to
     * accept a query id from other than the current result.
     *
     * @access     public
     * @param      resource [optional] the result from which the next row is to be fetched.
     * @return     mixed an array containing a DB record.
     */

    public function fetchAssoc($theQueryId = NULL)
    {
        if (($theQueryId !== NULL) && ($theQueryId != $this->queryid))
        {
            return $this->dbFetchAssoc($theQueryId) ;
        }
        else if (isset($this->queryid))
        {
            $this->previd++;
            return $this->record = $this->dbFetchAssoc($this->queryid);
        }
        else
        {
            $this->returnError('No query specified.');
        }

    }

    /**
     * Moves the record pointer to the first record
     *
     * @access     public
     * @param boolean $theAssocFlag True if an associative array is to be returned,
     *                false otherwise.
     * @return mixed An array containing the current DB record.
     */

    public function moveFirst($theAssocFlag=false)
    {
        if (isset($this->queryid)) {
            $t = @$this->db_data_seek($this->queryid, 0);

            if ($t) {
                $this->previd = 0;
                if ($theAssocFlag) {
                  return $this->fetchAssoc();
                } else {
                  return $this->fetchRow();
                }
            } else {
                $this->returnError('Cant move to the first record.');
            }
        } else {
            $this->returnError('No query specified.');
        }

    }

    /**
     * Moves the record pointer to the last record
     *
     * @access     public
     * @param boolean $theAssocFlag True if an associative array is to be returned,
     *                false otherwise.
     * @return mixed An array containing the current DB record.
     */

    public function moveLast($theAssocFlag=false)
    {
        if (isset($this->queryid)) {
            $this->previd = $this->resultCount()-1;

            $t = @$this->db_data_seek($this->queryid, $this->previd);

            if ($t) {
              if ($theAssocFlag) {
                return $this->fetchAssoc() ;
              } else {
                return $this->fetchRow();
              }
            } else {
                $this->returnError('Cant move to the last record.');
            }
        } else {
            $this->returnError('No query specified.');
        }

    }

    /**
     * Moves the record pointer to the next record
     *
     * @access     public
     * @param boolean $theAssocFlag True if an associative array is to be returned,
     *                false otherwise.
     * @return mixed An array containing the current DB record.
     */

   public function moveNext($theAssocFlag=false)
    {
      if ($theAssocFlag=false) {
        return $this->fetchAssoc() ;
      } else {
        return $this->fetchRow();
      }
    }

    /**
     * Moves the record pointer to the previous record
     *
     * @access     public
     * @param boolean $theAssocFlag True if an associative array is to be returned,
     *                false otherwise.
     * @return mixed An array containing the current DB record.
     */

    public function movePrev($theAssocFlag=false)
    {
        if (isset($this->queryid)) {
            if ($this->previd > 1) {
                $this->previd--;

                $t = @$this->db_data_seek($this->queryid, --$this->previd);

                if ($t) {
                  if ($theAssocFlag) {
                    return $this->fetchAssoc() ;
                  } else {
                    return $this->fetchRow();
                  }
                } else {
                    $this->returnError('Cant move to the previous record.');
                }
            } else {
                $this->returnError('BOF: First record has been reached.');
            }
        } else {
            $this->returnError('No query specified.');
        }

    } // end function


    /**
     * If the last query performed was an 'INSERT' statement, this method will
     * return the last inserted primary key number.  Many databases have a notion
     * of "sequence".  This function returns the value of the default sequence (for the case
     * of MySQL, its the last autoincrement field value) by default.  Should a
     * specifice sequence be needed, the optional name may be passed in and the current
     * value of that sequence will be returned instead.
     *
     * @param        string [optional] the name of the sequence whose current value is to be returned.
     * @return        int
     * @access        public
     * @scope        public
     * @since        version 1.0.1
     */
    public function fetchLastInsertId($theSequenceName = NULL)
    {
        $this->last_insert_id = @$this->db_insert_id($this->dblink, $theSequenceName);

        if (!$this->last_insert_id)
        {
            $this->returnError(
                sprintf('Unable to get the last inserted id from %s after query: %s',
                    $this->db_specialization(),
                    $this->lastQuery));
        }

        return $this->last_insert_id;

    } // end function

    /**
     * Returns state of queryid
     *
     * @param            none
     * @return           boolean
     * @access           public
     */
    public function eof()
    {
        $theResultCount = $this->resultCount() ;

        if ($this->resultCount === false)
        {
            return true ;
        }

        if ($theResultCount == 0)
        {
            return true ;
        }

        return false ;
    }

    /**
     * Counts the number of rows returned from a SELECT statement.
     *
     * @param      boolean [optional] true if errors are to be captured, false otherwise.  Default
     *                     is FALSE.
     * @return     mixed False if it is not possible to calculated the number of rows in
     *                   the result, otherwise the number of rows in the result.
     * @access     public
     */

    public function resultCount($showErrors=false)
    {
        if (isset($this->queryid))
        {
            $this->totalrecords = $this->numRows($this->queryid);

            return $this->totalrecords;
        }
        else
        {
            if ($showErrors)
            {
                $this->returnError('Unable to count the number of rows returned');
            }

            return false ;
        }

    }

    /**
     * Counts the number of rows affected by the last INSERT/UPDATE/DELETE statement.
     *
     * @return     integer The number of rows affected.
     * @access     public
     */

    public function affectedCount()
    {
        if (isset($this->_affectedRows))
        {
            return $this->_affectedRows ;
        }
        else
        {
            $this->returnError('Unable to count the number of rows affected');

            return false ;
        }

    }

    /**
     * Checks to see if there are any records that were returned from a
     * SELECT statement. If so, returns true, otherwise false.
     *
     * @return     boolean True if there were rows in the result, false otherwise.
     * @access     public
     */
    public function resultExist()
    {
        return (isset($this->queryid) && ($this->resultCount() > 0)) ;
    }

    /**
     * Checks to see if there are any records that were returned affected by a
     * INSERT/UPDATE/DELETE statement. If so, returns true, otherwise false.
     *
     * @access     public
     * @return     boolean True if there were any rows affected by the query, false otherwise.
     */

    public function affectedRows($dblink)
    {
        return (isset($this->_affectedRows) && ($this->_affectedRows > 0)) ;
    }

    /**
     * Clears any records in memory associated with a result set.
     * It allows calling with no select query having occurred.
     *
     * @param      resource $result
     * @access     public
     */

    public function clear($result = NULL)
    {
        if ($result !== NULL)
        {
            $t = $this->dbFreeResult($result);

            if (!$t)
            {
                $this->returnError('Unable to free the results from memory');
            }
        }
        else
        {
            if (isset($this->queryid) && !empty($this->queryid))
            {
                $this->dbFreeResult($this->queryid);
            }
        }

    }

    /**
     * Start a transaction.
     *
     * @access  public
     */

    public function beginTransaction()
    {
        if ($this->transactions_capable) {
            $this->queryConstant('BEGIN');
            $this->begin_work = true;
        }

    } // end function

    /**
     * Perform a commit to record the changes.
     *
     * @access  public
     */

    public function commitTransaction()
    {
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->queryConstant('COMMIT');
                $this->begin_work = false;
            }
        }
    }

    /**
     * Perform a rollback if the query fails.
     *
     * @access  public
     */

    public function rollbackTransaction()
    {
        if ($this->transactions_capable) {
            if ($this->begin_work) {
                $this->queryConstant('ROLLBACK');
                $this->begin_work = false;
            }
        }

    } // end function

    /**
    * Lock A Table Item.
    *
    * @access public
    * @param mixed Table Name(s).  If an array is passed, the key is the table name and the value
    *              is the type of lock requested for that table.
    * @param string A Lock Type
    * @return mixed false if the requested lock couldn't be granted, otherwise the
    *               result of the lock query.
    */

    public function lock($table, $mode="write")
    {
        $this->connect();

        $query="lock tables ";
        if (is_array($table))
        {
            foreach ($table as $key => $value)
            {
                if ($key=="read" && $key!=0)
                {
                    $query.="$value read, ";
                }
                else
                {
                    $query.="$value $mode, ";
                }
            }

            $query=substr($query,0,-2);
        }
        else
        {
            $query.="$table $mode";
        }

        $res = @$this->query($query, $this->dblink);

        if (!$res)
        {
            $this->returnError("$query failed.");
            return false ;
        }

        return $res;
    }

    /**
    * unlock A Table Item.
    *
    * @access public
    * @return mixed false if the unlock failed, the result of the query otherwise.
    */
    public function unlock()
    {
        $this->connect();

        $res = @$this->query("unlock tables");
        if (!$res)
        {
            $this->returnError("unlock() failed.");
            return false;
        }

        return $res;
    }
}