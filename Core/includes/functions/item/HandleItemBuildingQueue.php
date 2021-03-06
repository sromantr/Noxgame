<?php
/**
 * This file is part of Noxgame
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Copyright (c) 2012-Present, mandalorien
 * All rights reserved.
 *=========================================================
  _   _                                     
 | \ | |                                    
 |  \| | _____  ____ _  __ _ _ __ ___   ___ 
 | . ` |/ _ \ \/ / _` |/ _` | '_ ` _ \ / _ \
 | |\  | (_) >  < (_| | (_| | | | | | |  __/
 |_| \_|\___/_/\_\__, |\__,_|_| |_| |_|\___|
                  __/ |                     
                 |___/                                                                             
 *=========================================================
 *
 */
    function HandleItemBuildingQueue ( $CurrentUser, &$CurrentPlanet, $ProductionTime )
    {
        global $resource;

        if ($CurrentPlanet['b_item_id'] != 0)
        {
            $Builded                    = array ();
            $CurrentPlanet['b_item'] += $ProductionTime;
            $BuildQueue                 = explode(';', $CurrentPlanet['b_item_id']);
            $BuildArray                    = array();

            foreach ($BuildQueue as $Node => $Array)
            {
                if ($Array != '')
                {
                    $Item              = explode(',', $Array);
                    $AcumTime           = GetBuildingTime ($CurrentUser, $CurrentPlanet, $Item[0]);
                    $BuildArray[$Node] = array($Item[0], $Item[1], $AcumTime);
                }
            }

            $CurrentPlanet['b_item_id']     = '';
            $UnFinished                     = false;
                    foreach ( $BuildArray as $Node => $Item ){
                        $Element   = $Item[0];
                        $Count     = $Item[1];
                        $BuildTime = $Item[2];
                        $Builded[$Element] = 0;
                        if (!$UnFinished and $BuildTime > 0){
                            $AllTime = $BuildTime * $Count;
                            if($CurrentPlanet['b_item'] >= $BuildTime){
                                $Done = min($Count, floor( $CurrentPlanet['b_item'] / $BuildTime));
                                if($Count > $Done){
                                    $CurrentPlanet['b_item'] -= $BuildTime * $Done;                                
                                    $UnFinished = true;    
                                    $Count -= $Done;                                                        
                                }else{
                                    $CurrentPlanet['b_item'] -= $AllTime;                                        
                                    $Count = 0;
                                }
                                $Builded[$Element] += $Done;
                                $CurrentPlanet[$resource[$Element]] += $Done;
                            }else{
                                $UnFinished = true;    
                            }
                        }elseif(!$UnFinished){    
                                $Builded[$Element] += $Count;
                                $CurrentPlanet[$resource[$Element]] += $Count;                                
                                $Count = 0;                            
                        }
                        if ( $Count != 0 ){
                            $CurrentPlanet['b_item_id'] .= $Element.",".$Count.";";
                        }
                    }
        }
        else
        {
            $Builded                   = '';
            $CurrentPlanet['b_item'] = 0;
        }

        return $Builded;
    }
?>