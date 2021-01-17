<?php
// Script pour générer des données TSV depuis des notices JSON
// A été utilisé pour les peibntures et les sculptures
// Colonne sdu TSV :
/*
ID	
URL	
Musée	
Numéro d'inventaire	
Titre	
Auteur1-Nom	
Auteur1-Nais	
Auteur1-LieuNais	
Auteur1-Mort	
Auteur1-LieuMort	
Auteur1-Fonction	
Auteur2-Nom	
Auteur2-Nais	
Auteur2-LieuNais	
Auteur2-Mort	
Auteur2-LieuMort	
Auteur2-Fonction	
Auteur3-Nom	
Auteur3-Nais	
Auteur3-LieuNais	
Auteur3-Mort	
Auteur3-LieuMort	
Auteur3-Fonction	
Autres Auteurs	
Image 1	
Image 2	
Images autres	
date unique	// 2 valeurs "date unique" ou "période". "date unique"=> Date1 uniquement ; "période"=> Date1 et Date2
Date1-Indication	
Date1-Precision	
Date1-Jour	
Date1-Mois	
Date1-Annee	
Date2-Indication	
Date2-Precision	
Date2-Jour	
Date2-Mois	
Date2-Annee	
Siècle	
Largeur	// en cm ; porte sur l'œuvre
Hauteur	// en cm ; porte sur l'œuvre
Profondeur/Epaisseur	// en cm ; porte sur l'œuvre
Diamètre	// en cm ; porte sur l'œuvre
Cadre Largeur	// en cm
Cadre Hauteur	// en cm
Cadre Profondeur/Epaisseur	// en cm
Cadre Diamètre	// en cm
Thème	
Style	
Denomation principale	
Autres dénominations	
Mode d'acquisition	
Date d'acquisition	
Donateur //	généralement donateur, voire vendeur pour les achats
Donateurs Autres	
MatTech1	
MatTech2	
MatTech3	
MatTech4	
MatTech5	
MatTech6	
MatTech7	
MatTech8	
Lieu //	lieu précis dans le musée
Inscription-Type1	
Inscription-Ecriture1	
Inscription-Texte1	
Inscription-Type2	
Inscription-Ecriture2	
Inscription-Texte2	
Inscription-Type3	
Inscription-Ecriture3	
Inscription-Texte3	
Inscription-Type4	
Inscription-Ecriture4	
Inscription-Texte4	
Inscription-Type5	
Inscription-Ecriture5	
Inscription-Texte5	
Inscription-Type6	
Inscription-Ecriture6	
Inscription-Texte6	
Inscription-Type7	
Inscription-Ecriture7	
Inscription-Texte7	
Inscription-Type8	
Inscription-Ecriture8	
Inscription-Texte8	
Inscription-Type9	
Inscription-Ecriture9	
Inscription-Texte9	
*/
// Quelques remarques :
/*

* Il peut y avoir plus d'un auteur. Il y a 4 groupe de colonnes dans le tableau : Auteur 1 (avec colonnes de nom, lieu et dates de vie et de mort, fonction), Auteur 2 (même colonnes que Auteur 1), Auteur 3 (même colonnes que Auteur 1 et 2), Autres auteurs (colonne unique avec tout à la suite ; cas rares).A regarder les données il manque dans les données de l'API certaines qualifications pour les auteurs, comme "anciennement attribué à". Du coup les Auteur 2 ne peuvent être ingérés directement, hormis peut-être ce qui en fonction "Auteur du modèle" qui correspond couramment à "d'après" (exemple sur Wikidata). En bref, seul Auteur 1 paraît fiable.
Exemple  notice HTML / JSON :
- https://www.parismuseescollections.paris.fr/fr/petit-palais/oeuvres/offrande-a-pan#infos-secondaires-detail
Auteur(s): 
Attribué à Vallin, Jacques-Antoine (Paris, vers 1760 - après 1831), peintre
Anciennement attribué à Taraval, Hugues (Paris, 01–01–1729 - Paris, 01–01–1785), peintre

- Sur la requête :
{
  nodeQuery(
    filter: {conditions: [{field: "uuid", value: "fcadccfc-d87c-4254-8f96-773127480750"}]}
  ) {
    entities {
      entityUuid
      ... on NodeOeuvre {
        title
        absolutePath
        fieldLrefAdlib
        fieldOeuvreAuteurs {
          entity {
            fieldAuteurAuteur {
              entity {
                name
                fieldPipDateNaissance {
                  startYear
                }
                fieldPipLieuNaissance
                fieldPipDateDeces {
                  startYear
                }
                 fieldLieuDeces
              }
            }
            fieldAuteurFonction {
              entity {
                name
              }
            }
          }
        }
       
        fieldOeuvreNumInventaire
       
        fieldMusee {
          entity {
            name
          }
        }
       
      }
    }
  }
}
Réponse JSON :
{
  "data": {
    "nodeQuery": {
      "entities": [
        {
          "entityUuid": "fcadccfc-d87c-4254-8f96-773127480750",
          "title": "Nymphes",
          "absolutePath": "https://parismuseescollections.paris.fr/node/226418",
          "fieldLrefAdlib": 160007643,
          "fieldOeuvreAuteurs": [
            {
              "entity": {
                "fieldAuteurAuteur": {
                  "entity": {
                    "name": "Vallin, Jacques-Antoine",
                    "fieldPipDateNaissance": {
                      "startYear": 1760
                    },
                    "fieldPipLieuNaissance": "Paris",
                    "fieldPipDateDeces": {
                      "startYear": 1831
                    },
                    "fieldLieuDeces": null
                  }
                },
                "fieldAuteurFonction": {
                  "entity": {
                    "name": "Peintre"
                  }
                }
              }
            },
            {
              "entity": {
                "fieldAuteurAuteur": {
                  "entity": {
                    "name": "Taraval, Hugues",
                    "fieldPipDateNaissance": {
                      "startYear": 1729
                    },
                    "fieldPipLieuNaissance": "Paris",
                    "fieldPipDateDeces": {
                      "startYear": 1785
                    },
                    "fieldLieuDeces": "Paris"
                  }
                },
                "fieldAuteurFonction": {
                  "entity": {
                    "name": "Peintre"
                  }
                }
              }
            }
          ],
          "fieldOeuvreNumInventaire": "PPP3001",
          "fieldMusee": {
            "entity": {
              "name": "Petit Palais, musée des Beaux-arts de la Ville de Paris"
            }
          }
        }
      ]
    }
  }
}

* Outre les images et les auteurs, il y a d'autres champs multiples. 2 approches :
    - 1 colonne principale et une colonne pour les autres valeurs (cas rares)
           Dénomination et Donateur
    - 1 colonne par élément
          "Matériaux et techniques"  et "Inscriptions"
Ca explique pourquoi il y 8 colonnes MatTech et 9 groupes de colonnes inscriptions 

* Pour les dimensions elles portent soit sur l'œuvre soit sur le cadre. Si la précision de partie n'est pas indiquée (cas nombreux) c'est la dimension oeuvre qui est renseignée.

* Pour les dates, ajout d'une colonne "date unique avec 2 valeurs possibles :
    - "date unique"           seules les données Date1 sont à prendre en compte
    - "période"          données Date1 et Date2 sont à prendre en compte
* Les dimensions sont en cm

*/
// C:\xampp\php\php E:\htdocs\wd\pm\sculpt.php
set_time_limit(3600);

$dirname = 'E:/WD/parismusee/n2/';
$dir = opendir($dirname); 
$ficsortie = fopen("E:/htdocs/wd/pm/data_sculpt.tsv", 'w+');

$cpt=0;
while($file = readdir($dir)) {
	//if ($cpt==20) break;  // Test
	echo $cpt."\n";
	if($file != '.' && $file != '..' && !is_dir($dirname.$file)){
		$id=str_replace(".json","",$file);
		$cpt++;
		
$datafic=file_get_contents($dirname.$id.".json",true);
$data = json_decode($datafic,true);

$entity=$data["data"]["nodeQuery"];
foreach ($entity["entities"]  as $notice){

$url="https://www.parismuseescollections.paris.fr/fr/".$notice["fieldUrlAlias"];
$inv=$notice["fieldOeuvreNumInventaire"];
$museelong=explode(",",$notice["fieldMusee"]["entity"]["name"]);
$musee=$museelong[0];
$titre=str_replace("\t","",$notice["title"]);
$aut1="";
$nais1="";
$lieun1="";
$mort1="";
$lieum1="";
$fonct1="";
$aut2="";
$nais2="";
$lieun2="";
$mort2="";
$lieum2="";
$fonct2="";
$aut3="";
$nais3="";
$lieun3="";
$mort3="";
$lieum3="";
$fonct3="";
$autautres="";
$img1="";
$img2="";
$imgautres="";
$rang=1;
$dateunique="";
$Date1indic="";
$Date1prec="";
$Date1jour="";
$Date1mois="";
$Date1annee="";
$Date2indic="";
$Date2prec="";
$Date2jour="";
$Date2mois="";
$Date2annee="";
$siecle="";
$theme="";
$deno="";
$deno_autres="";
$donateur="";
$donateur_autres="";
$mat1="";
$mat2="";
$mat3="";
$mat4="";
$mat5="";
$mat6="";
$mat7="";
$mat8="";
$lieu="";
$mouvement="";
$larg="";
$haut="";
$prof="";
$diam="";
$cadrelarg="";
$cadrehaut="";
$cadreprof="";
$cadrediam="";
$insc=array();
for ($i=0;$i<10;$i++){
	$insc[$i]["type"]="";
	$insc[$i]["ecriture"]="";
	$insc[$i]["texte"]="";
}
if ($notice["fieldOeuvreSiecle"]){
	foreach ($notice["fieldOeuvreSiecle"] as $value){
		$siecle=$value["name"];
	}
}
if ($notice["fieldOeuvreThemeRepresente"]){
	foreach ($notice["fieldOeuvreThemeRepresente"]  as $value){
		foreach ($value  as $valterme)
			$theme=$valterme["name"];
	}
}
if ($notice["fieldOeuvreStyleMouvement"]){
	foreach ($notice["fieldOeuvreStyleMouvement"]  as $value){
		foreach ($value  as $valterme)
			$mouvement=$valterme["name"];
	}
}
if ($notice["fieldDenominations"]){
	foreach ($notice["fieldDenominations"]  as $value){
		foreach ($value  as $valterme){
			if ($deno=="")
				$deno=$valterme["name"];
			else{
				if 	($deno_autres!="")
					$deno_autres.=";";
				$deno_autres.=$valterme["name"];
			}
		}
	}
}
$modacqu="";
if ($notice["fieldModaliteAcquisition"]){
	foreach ($notice["fieldModaliteAcquisition"]  as $value){
		foreach ($value  as $valterme)
			$modacqu=$valterme;
	}
}
$dateacqu="";
if ($notice["fieldDateAcquisition"])
	$dateacqu=$notice["fieldDateAcquisition"]["startYear"];
if ($notice["fieldDonateurs"]){
	foreach ($notice["fieldDonateurs"]  as $value){
		foreach ($value  as $valterme){
			if ($donateur=="")
				$donateur=$valterme["name"];
			else{
				if 	($donateur_autres!="")
					$donateur_autres.=";";
				$donateur_autres.=$valterme["name"];
			}
		}
	}
}	
if ($notice["fieldMateriauxTechnique"]){
	foreach ($notice["fieldMateriauxTechnique"]  as $value){
		foreach ($value  as $valterme){
			if ($mat1=="")
				$mat1=$valterme["name"];
			elseif($mat2=="")
				$mat2=$valterme["name"];
			elseif($mat3=="")
				$mat3=$valterme["name"];
			elseif($mat4=="")
				$mat4=$valterme["name"];
			elseif($mat5=="")
				$mat5=$valterme["name"];
			elseif($mat6=="")
				$mat6=$valterme["name"];
			elseif($mat7=="")
				$mat7=$valterme["name"];
			elseif($mat8=="")
				$mat8=$valterme["name"];
		}
	}
}	
if ($notice["fieldOeuvreExpose"]){
	foreach ($notice["fieldOeuvreExpose"] as $value){
		$lieu=$value["name"];
	}
}
$rginsc=0;
if ($notice["fieldOeuvreInscriptions"]){
	foreach ($notice["fieldOeuvreInscriptions"]  as $value){
		foreach ($value  as $valins){
			if ($valins["fieldInscriptionType"])
				foreach ($valins["fieldInscriptionType"]  as $valterme)
					$insc[$rginsc]["type"]=$valterme["name"];
			if ($valins["fieldInscriptionEcriture"])
				foreach ($valins["fieldInscriptionEcriture"]  as $valterme)
					$insc[$rginsc]["ecriture"]=$valterme["name"];
			if ($valins["fieldInscriptionMarque"])
				foreach ($valins["fieldInscriptionMarque"]  as $valterme)
					$insc[$rginsc]["texte"]=$valterme;
			$rginsc++;
		}
	}
}
if ($notice["fieldOeuvreDimensions"]){
	foreach ($notice["fieldOeuvreDimensions"]  as $value){
		foreach ($value  as $valdim){
			$partie="";
			$dimension="";
			$val="";
			if ($valdim["fieldDimensionPartie"])
				foreach ($valdim["fieldDimensionPartie"]  as $valterme)
					$partie=$valterme["name"];
			if ($valdim["fieldDimensionType"])
				foreach ($valdim["fieldDimensionType"]  as $valterme)
					$dimension=$valterme["name"];
			$val=$valdim["fieldDimensionValeur"];	

			if (($partie=="Œuvre")||($partie=="")){
				if ($dimension=="Diamètre")	
					$diam=$val;
				if ($dimension=="Largeur")	
					$larg=$val;
				if ($dimension=="Hauteur")	
					$haut=$val;
				if (($dimension=="Epaisseur")||($dimension=="Profondeur"))
					$prof=$val;
			}
			if ($partie=="Cadre"){
				if ($dimension=="Diamètre")	
					$cadrediam=$val;
				if ($dimension=="Largeur")	
					$cadrelarg=$val;
				if ($dimension=="Hauteur")	
					$cadrehaut=$val;
				if (($dimension=="Epaisseur")||($dimension=="Profondeur"))
					$cadreprof=$val;
			}
		}
	}
}

if ($notice["fieldOeuvreAuteurs"]){
	foreach ($notice["fieldOeuvreAuteurs"] as $value){
		foreach ($value as $data_aut){
			$aut=$data_aut["fieldAuteurAuteur"]["entity"];
			if ($rang==1){
				$aut1=$aut["name"];
				$nais1=$aut["fieldPipDateNaissance"]["startYear"];
				$lieun1=$aut["fieldPipLieuNaissance"];
				$mort1=$aut["fieldPipDateDeces"]["startYear"];
				$lieum1=$aut["fieldLieuDeces"];
				if ($data_aut["fieldAuteurFonction"]["entity"]){
					foreach($data_aut["fieldAuteurFonction"]["entity"] as $fonct){
						if ($fonct1!="")
							$fonct1.=";";
						$fonct1.=$fonct;
					}
				}
			}
			elseif ($rang==2){
				$aut2=$aut["name"];
				$nais2=$aut["fieldPipDateNaissance"]["startYear"];
				$lieun2=$aut["fieldPipLieuNaissance"];
				$mort2=$aut["fieldPipDateDeces"]["startYear"];
				$lieum2=$aut["fieldLieuDeces"];
				if ($data_aut["fieldAuteurFonction"]["entity"]){
					foreach($data_aut["fieldAuteurFonction"]["entity"] as $fonct){
						if ($fonct2!="")
							$fonct2.=";";
						$fonct2.=$fonct;
					}
				}
			}
			elseif ($rang==3){
				$aut3=$aut["name"];
				$nais3=$aut["fieldPipDateNaissance"]["startYear"];
				$lieun3=$aut["fieldPipLieuNaissance"];
				$mort3=$aut["fieldPipDateDeces"]["startYear"];
				$lieum3=$aut["fieldLieuDeces"];
				if ($data_aut["fieldAuteurFonction"]["entity"]){
					foreach($data_aut["fieldAuteurFonction"]["entity"] as $fonct){
						if ($fonct3!="")
							$fonct3.=";";
						$fonct3.=$fonct;
					}
				}
			}
			else{
				if ($autautres!="")
					$autautres.=";";
				$autautres.=$aut["name"];
				$autautres.=" (".$aut["fieldPipDateNaissance"]["startYear"];
				$autautres.=", ".$aut["fieldPipLieuNaissance"];
				$autautres.=" - ".$aut["fieldPipDateDeces"]["startYear"];
				$autautres.=", ".$aut["fieldLieuDeces"].")";
				if ($data_aut["fieldAuteurFonction"]["entity"]){
					foreach($data_aut["fieldAuteurFonction"]["entity"] as $fonct){
						$autautres.=" - ".$fonct;
					}
				}
			}
			$rang++;
		}
	}
}
$rang=1;
if ($notice["queryFieldVisuels"]){
	foreach ($notice["queryFieldVisuels"]["entities"] as $img){
		if ($rang==1){
			$img1=$img["publicUrl"];
		}
		elseif ($rang==2){
			$img2=$img["publicUrl"];
		}
		else{
			if ($imgautres!="")
				$imgautres.=";";
			$imgautres.=$img["publicUrl"];
		}
		$rang++;
	}
}
if ($notice["fieldDateProduction"]){
	$dateunique="date unique";
	if (!is_null($notice["fieldDateProduction"]["endYear"]))
		$dateunique="période";
	$Date1indic=$notice["fieldDateProduction"]["startPrecision"];
	$Date1prec="annee";
	if (!is_null($notice["fieldDateProduction"]["startMonth"]))
		$Date1prec="mois";
	if (!is_null($notice["fieldDateProduction"]["startDay"]))
		$Date1prec="jour";
	$Date1jour=$notice["fieldDateProduction"]["startDay"];
	$Date1mois=$notice["fieldDateProduction"]["startMonth"];
	$Date1annee=$notice["fieldDateProduction"]["startYear"];
	$Date2indic=$notice["fieldDateProduction"]["endPrecision"];
	if (!is_null($notice["fieldDateProduction"]["endYear"]))
		$Date2prec="annee";
	if (!is_null($notice["fieldDateProduction"]["endMonth"]))
		$Date2prec="mois";
	if (!is_null($notice["fieldDateProduction"]["endDay"]))
		$Date2prec="jour";
	$Date2jour=$notice["fieldDateProduction"]["endDay"];
	$Date2mois=$notice["fieldDateProduction"]["endMonth"];
	$Date2annee=$notice["fieldDateProduction"]["endYear"];
}
fputs($ficsortie,$id."	".
$url."	".
$musee."	".
$inv."	".
$titre."	".
$aut1."	".
$nais1."	".
$lieun1."	".
$mort1."	".
$lieum1."	".
$fonct1."	".
$aut2."	".
$nais2."	".
$lieun2."	".
$mort2."	".
$lieum2."	".
$fonct2."	".
$aut3."	".
$nais3."	".
$lieun3."	".
$mort3."	".
$lieum3."	".
$fonct3."	".
$autautres."	".
$img1."	".
$img2."	".
$imgautres."	".
$dateunique."	".
$Date1indic."	".
$Date1prec."	".
$Date1jour."	".
$Date1mois."	".
$Date1annee."	".
$Date2indic."	".
$Date2prec."	".
$Date2jour."	".
$Date2mois."	".
$Date2annee."	".
$siecle."	".
$larg."	".
$haut."	".
$prof."	".
$diam."	".
$cadrelarg."	".
$cadrehaut."	".
$cadreprof."	".
$cadrediam."	".
$theme."	".
$mouvement."	".
$deno."	".
$deno_autres."	".
$modacqu."	".
$dateacqu."	".
$donateur."	".
$donateur_autres."	".
$mat1."	".
$mat2."	".
$mat3."	".
$mat4."	".
$mat5."	".
$mat6."	".
$mat7."	".
$mat8."	".
$lieu."	".
$insc[0]["type"]."	".
$insc[0]["ecriture"]."	".
$insc[0]["texte"]."	".
$insc[1]["type"]."	".
$insc[1]["ecriture"]."	".
$insc[1]["texte"]."	".
$insc[2]["type"]."	".
$insc[2]["ecriture"]."	".
$insc[2]["texte"]."	".
$insc[3]["type"]."	".
$insc[3]["ecriture"]."	".
$insc[3]["texte"]."	".
$insc[4]["type"]."	".
$insc[4]["ecriture"]."	".
$insc[4]["texte"]."	".
$insc[5]["type"]."	".
$insc[5]["ecriture"]."	".
$insc[5]["texte"]."	".
$insc[6]["type"]."	".
$insc[6]["ecriture"]."	".
$insc[6]["texte"]."	".
$insc[7]["type"]."	".
$insc[7]["ecriture"]."	".
$insc[7]["texte"]."	".
$insc[8]["type"]."	".
$insc[8]["ecriture"]."	".
$insc[8]["texte"]."	".
$insc[9]["type"]."	".
$insc[9]["ecriture"]."	".
$insc[9]["texte"]."	".
"\n");
		}//end foreach ($entity["entities"]  as $notice)
	}//it's a file fichier
}//reading files in directory
fclose($ficsortie);
closedir($dir);
echo "\ncompilation done";


?>