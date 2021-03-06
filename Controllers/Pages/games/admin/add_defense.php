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

	if ($user['authlevel'] >= 2) {
		includeLang('admin');
		includeLang('tech');

		$mode      = $_POST['mode'];

		$PageTpl   = gettemplate("admin/add_defense");
		$parse     = $lang;
		
			$parse['adm_am_gala']=$lang['adm_am_gala'];
			$parse['adm_am_syst']=$lang['adm_am_syst'];
			$parse['adm_am_plant']=$lang['adm_am_plant'];
			$parse['adm_am_type']=$lang['adm_am_type'];
			$parse['adm_am_coor']=$lang['adm_am_coor'];
			$parse['adm_am_ress']=$lang['adm_am_ress'];

			// $defense = "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
			foreach ($reslist['defense']  as $n => $i) {
				// if ($planetrow[$resource[$i]] > 0) {
						$defense .= "<tr height=\"20\">";
						// $defense .= "<th>".$lang['tech'][$i]."</th>";
						$defense .= "<th><span id='txtHint'></span></th>";
						$defense .= "<th>".$utili[$resource[$i]]."</th>";
						// $defense .= "<th><input name=\"defense". $i ."\" size=\"10\" value=\"0\"/></th>";
						$defense .= "</tr>";
				// }
			}
			// $defense .= "</table>";
			
		$parse['defense'] = $defense;
		if ($mode == 'addit') {
			
			$gala        = intval($_POST['gala']);
			$syst        = intval($_POST['syst']);
			$plant        = intval($_POST['plant']);
			$typeplant     = intval($_POST['typeplant']);

			$planetexist = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '" . $gala . "' AND `system` = '" . $syst . "' AND `planet` = '" . $plant . "';", 'planets', true);

			if($planetexist != false)
			{
					foreach ($reslist['defense']  as $n => $i) 
					{
						$valeur = intval($_POST["defense$i"]);
						var_dump($valeur);
					}
				$QryUpdatePlanet  = "UPDATE {{table}} SET ";
					foreach ($reslist['defense']  as $n => $i) 
					{
						$QryUpdatePlanet .= "`". $resource[$i] ."` = `". $resource[$i] ."` + '". intval($_POST["defense$i"]) ."', ";
					}
						$QryUpdatePlanet .= "`id_level` = ''";
						$QryUpdatePlanet .= "WHERE ";
						$QryUpdatePlanet .= "`galaxy` = '". $gala ."' AND";
						$QryUpdatePlanet .= "`system` = '". $syst ."' AND";
						$QryUpdatePlanet .= "`planet` = '". $plant ."' AND";
						$QryUpdatePlanet .= "`planet_type` = '". $typeplant ."';";
						$valeur=doquery($QryUpdatePlanet, 'planets');
						
				AdminMessage ( $lang['adm_am_done'], $lang['adm_am_ttle'] );

			}
			else
			{
				AdminMessage ($lang['adm_am_error1']." ".$gala.":".$syst.":".$plant." ".$lang['adm_am_error2'], $lang['adm_am_ttle'] );
			}
		}
		$Page = parsetemplate($PageTpl, $parse);

					//si le mode frame est activ� alors
			if($game_config['frame_disable'] == 1)
			{
				frame ( $Page, $lang['sys_overview'], false, '', true);
			}
			elseif($game_config['frame_disable'] == 0)
			{
				display ($Page, $title = 'generale', $topnav = false, $metatags = '', $AdminPage = true, $leftMenu = false,$leftMenuAdmin = true);
			}
	} else {
		AdminMessage ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}

?>