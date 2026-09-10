<?php
   
    class TemplateParser {
        
        private $tokenPattern4 = '~{{(for |if |include|else |fornull |end )?([0-9.A-Za-z_: ]+?)(?:\(([^\}]+?)\))?}}~si';
        
        private $tpl = '';
        private $vars = null;
        
        private $Alias ='';
       
        private $matchList = null;
        
        private $valueTokens = null;
        
        public function __construct() {
            $this->valueTokens = array();
        }
        
        public function getTemplate() {
            return $this->tpl;
        }
        
        public function getValueTokens() {
            return $this->valueTokens;
        }
        
        public function setParams($TplContents,$TplVars,$matches=null) {
            $this->matchList = $matches;
            $this->tpl = $TplContents;
            if (($TplVars instanceof EntityBase) || ($TplVars instanceof EntityCollectionBase))
                    $TplVars = $TplVars->toArray();
            $this->vars = $TplVars;
        }
        
        public function setScope($ScopeAlias) {
            $this->Alias = $ScopeAlias;
        }
        

        public function ParseTpl() {
            $cnt = 0;
            $matches = array();
            if ($this->matchList!=null) {
                $matches = $this->matchList;
                $cnt = count($matches);
            } else {
                $cnt = preg_match_all( $this->tokenPattern4 , $this->tpl, $matches );
            }
            if ($cnt===false) {
                throw new ETemplateError('Syntax error');   
            }
            $i = 0;
            $cnt = count($matches[0]);
            $values = array();
            for ($i = 0; $i <= $cnt-1; $i++) {
                $token = trim($matches[2][$i]);
               
                if ($matches[1][$i]=='if ') {
                   $blocks = $this->getBlock($this->tpl,$matches[0][$i],'{{else '.$token.'}}','{{end if '.$token.'}}');
                   if ($blocks == null) continue;
                   if ($this->compare($token,$this->getValue($token),$matches[3][$i])) {
                        $this->tpl = mb_substr( $this->tpl, 0, $blocks['start'] ).$blocks['true'].mb_substr( $this->tpl,$blocks['end']);
                   } else {
                        $this->tpl = mb_substr( $this->tpl, 0, $blocks['start'] ).$blocks['false'].mb_substr( $this->tpl,$blocks['end']);
                   };
                   continue;
                } // end if
                
                if ($matches[1][$i]=='for ') {
                    $parts = explode(' as ',$token);
                    if (count($parts)<2) throw new ETemplateError('Variable alias in for block not found');
                    $token= trim($parts[0]);
                    $scopeAlias = $parts[1];
                    
                    $blocks = $this->getBlock($this->tpl,$matches[0][$i],'{{fornull '.$token.'}}','{{end for '.$token.'}}');
                    if ($blocks == null) continue;
                    $value =$this->getValue($token);
                    
                    
                    $fromArray = array_slice($matches[0],$i);
                    $BlockEndIndex = array_search('{{end for '.$token.'}}',$fromArray);
                    $blockVars = $matches;
                    if ($blocks['else']===false) {
                        $blockVars[0] = array_slice($matches[0],$i+1,$BlockEndIndex-1); 
                        $blockVars[1] = array_slice($matches[1],$i+1,$BlockEndIndex-1);
                        $blockVars[2] = array_slice($matches[2],$i+1,$BlockEndIndex-1); 
                        $blockVars[3] = array_slice($matches[3],$i+1,$BlockEndIndex-1); 
                    } else {
                        $blockElseIndex = array_search('{{fornull '.$token.'}}',$fromArray);
                        $blockVars[0] = array_slice($matches[0],$i+1,$blockElseIndex-1); 
                        $blockVars[1] = array_slice($matches[1],$i+1,$blockElseIndex-1);
                        $blockVars[2] = array_slice($matches[2],$i+1,$blockElseIndex-1);
                        $blockVars[3] = array_slice($matches[3],$i+1,$blockElseIndex-1);
                    }
                   
                    if ($value!=null && is_array($value) && count($value)>0) {
                        $n=0;
                        $delimiter='';
                        $DelimJoin = trim($matches[3][$i]);
                        $extra = 0;
                        if ($DelimJoin!='') {
                            list($n,$delimiter) = explode(',',$DelimJoin,2);
                            $n = trim($n);
                            if (substr($n, 0 ,1)=='c') {
                                $ValCount = count($value);
                                $cols = intval(substr($n,1));
                                $n = floor($ValCount / $cols);
                                $extra = $ValCount % $cols;
                            } else {
                                $n=intval($n);    
                            }
                            $delimiter = mb_substr($delimiter,1,mb_strlen($delimiter)-2);
                        }

                        $replace = '';
                        $ValueCount = count($value);
                        $index = 0;
                        $ItemFetched = 0;
                        //for ($j = 0; $j < $ValueCount; $j++) {
                        $delimCount = 0;    
                        foreach($value as $j => $valueJ) {
                            $parser = new TemplateParser();
                            $parser->setParams($blocks['true'], $valueJ,$blockVars);
                            $parser->setScope($scopeAlias);
                            $parser->ParseTpl();
                            $replace.=$parser->getTemplate();
                            $this->valueTokens = $parser->valueTokens;
                            //$values = array_merge($values,$parser->valueTokens);
                            ++$ItemFetched;
                            ++$index;
                            $ExItem = 0;
                            if ($extra>0 && $delimCount<$extra)
                                $ExItem=1;
                            if ($ItemFetched==($n+$ExItem) && $index!==$ValueCount) {
                                $ItemFetched=0;
                                $replace.=$delimiter;   
                                ++$delimCount;
                            }
                        }
                        $this->tpl = mb_substr( $this->tpl, 0, $blocks['start'] ).$replace.mb_substr( $this->tpl,$blocks['end']);
                    } else {
                        $this->tpl = mb_substr( $this->tpl, 0, $blocks['start'] ).$blocks['false'].mb_substr( $this->tpl,$blocks['end']);
                    }
                    
                    
                    $i =$i + $BlockEndIndex;  
                    continue;
                } // end for
                
               
                if ($matches[1][$i]=='include') {
                    $tplp = new Template($matches[3][$i]);
                    $tplp->vars = $this->vars;
                    $parsedInclude = $tplp->Render();
                    $this->tpl = str_replace($matches[0][$i],$parsedInclude,$this->tpl);
                    continue;
                } // end include
                
                if (empty($matches[1][$i]) && !empty($token)) {
                    $values[] = $token;
                }
            }
            //$values = array_unique($values);
            $this->valueTokens = array_values(array_unique($this->valueTokens));
            $cnt = count($this->valueTokens);
            if ($cnt>0) {
                for ($i = 0; $i <= $cnt-1; $i++) {
                    $value =$this->getValue($this->valueTokens[$i]);
                    //echo_line('Scope replacing '.$values[$i].' = '.$value);
                    if ($value !== null) {
                        $token = '{{'.$this->valueTokens[$i].'}}';
                        $this->tpl = str_replace($token,$value,$this->tpl); 
                    }
                }
            }
            
            $cnt = count($values);
            if ($cnt>0) {
                for ($i = 0; $i <= $cnt-1; $i++) {
                    $tname= $values[$i];
                    if (strpos($tname,':c')>0) {
                        $tname = str_replace(':c', '',$tname);
                        $value = sprintf(HttpContext::current()->culture()->CurrencyFormat(), number_format(floatval($this->getValue($tname)),HttpContext::current()->culture()->CurrencyDecimals()) );
                    } else {
                        $value =$this->getValue($tname);
                    }
                    //echo_line('replacing '.$values[$i].' = '.$value);
                    if ($value !== null) {
                        $this->tpl = str_replace('{{'.$values[$i].'}}',strval($value),$this->tpl); 
                    }
                }
            }
            
            
            
           
            /*
            echo '<div style="border:1px solid red;"><br>'.$this->Alias; 
            pre_arr($values);
            pre_arr($this->valueTokens);
            echo '</div>';
            echo_line('');
             * 
             */
        }
        
       
        
        private function getValue($token) {
            
            $vle = '';
            
            if (empty($this->Alias)) {
                // root parse
                if (strpos($token,'.')===false) {
                    $vle = $this->vars[$token];
                } else {
                    $vle =ArrayObjectExt::Retrive($this->vars,$token);
                }
            } else {
                // child parse
                if (strpos($token,$this->Alias.'.')===0) {
                    $token = substr($token,strlen($this->Alias)+1);
                    if (strpos($token,'.')===false) {
                        $vle = $this->vars[$token];
                    } else {
                        $vle =ArrayObjectExt::Retrive($this->vars,$token);
                    }
                } elseif ($token==$this->Alias) {
                        $vle = $this->vars;
                } else {
                    //if (!in_array($token, $this->valueTokens))
                        $this->valueTokens[] = $token;
                        return null;
                }
            }
            
            if (($vle instanceof EntityBase) || ($vle instanceof EntityCollectionBase))
                    $vle = $vle->toArray();
            return $vle;
              
        }
        
        private function getBlock($content,$Fulltoken,$else,$end) {
           
           $tplStartPos = mb_strpos($content,$Fulltoken);
           if ($tplStartPos === false) return null;
           $tplEndPos = mb_strpos($content,$end,$tplStartPos);
           if ($tplEndPos === false) {
                $tokenCleaned = str_replace('{{', '', $Fulltoken);
                $tokenCleaned = str_replace('}}', '', $tokenCleaned);
           
               throw new ETemplateError('Closing tag of block not found  : Token = '.$tokenCleaned.' ');
           }
           $AfterToken =$tplStartPos+  mb_strlen($Fulltoken);
           $BlockTpl = mb_substr($content,$AfterToken,$tplEndPos - $AfterToken);
           $elsePos = mb_strpos($BlockTpl,$else);
           
           $ret = array();
           $ret['true'] = $elsePos===false?$BlockTpl:mb_substr($BlockTpl,0,$elsePos);
           $ret['false'] = $elsePos===false?'':mb_substr($BlockTpl,$elsePos + strlen($else));
           $ret['else'] = ($elsePos!==false);
           $ret['start'] = $tplStartPos;
           $ret['end'] = $tplEndPos + strlen($end);
           return $ret; 
        }
        
        
        public function CleanTpl() {
            $this->tpl = preg_replace( "|{{.*?}}|si", "", $this->tpl ); 
        }
        
        
        
        
        private function compare($token,$value,$cond) {
            if (empty($cond)) {
                if (empty($value)) return false;
                return true;
            }
            $bl = true;
            if (substr($cond,0,1)=='!') {
                $cond = substr($cond,1);
                $bl = false;
            }
            
            /*
            if (substr($cond,1,1)=='$') {
                $tempVal = $this->getValue(substr($cond, 2));
                $cond =substr($cond, 0,1).$tempVal;
            }
             * 
             */
            
            $res = false;
            if ($cond == 'null') {
               $res = !isset($value) || $value=='';
            } elseif (substr($cond,0,1) == '>') {
               $res = isset($value) && $value>floatval(substr($cond,1)); 
            } elseif (substr($cond,0,1) == '<') {
               $res = isset($value) && $value<floatval(substr($cond,1)); 
            } elseif (substr($cond,0,1) == '=') {
               $res = isset($value) && $value==substr($cond,1); 
            } else {
               throw new ETemplateError('Wrong condition in IF statement : [ '.$token.$cond.' ]');  
            }
            
            // if(eval(("return $condition;"))) return str_replace( '"', '"', $block );
            if ($bl==true) {
                return $res;  } else { return !$res; };
        }
        
        
        public function CleanUrls() {
            preg_replace_callback('#href="([^"]*)"#is', array(&$this,'encodeurls'), $this->tpl);
        }
        

        private function encodeurls($match){
            //return 'href="'.urlencode($match[1]).'"';
            $ret = htmlspecialchars( $match[1], ENT_QUOTES, 'UTF-8', false );
            return 'href="'.$ret.'"';
        }
        
        
        
        

    }


?>