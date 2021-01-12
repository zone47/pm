#C:\Python27\python E:\WD\parismusee\getn.py
#Script pour recuperer les donnees d'une notice
import requests
import json
import os

os.chdir("E:/WD/parismusee")
fic = open("id.txt", "r")
url = 'http://apicollections.parismusees.paris.fr/graphql'
headers={'auth-token': '***', 'Content-Type': 'application/json'}

dataids = fic.read()
fic.close()
tabids=dataids.split('\n')

os.chdir("E:/WD/parismusee/n")
for id in tabids:
  id=str(id)
  print(id)
  query = """
{
  nodeQuery(
    filter: {conditions: [{field: \"uuid\", value: \""""+id+"""\"}]}
  ){
    entities {
      entityUuid
      ... on NodeOeuvre {
      title
      absolutePath
      fieldUrlAlias
      fieldTitreDeMediation
      fieldSousTitreDeMediation
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
      queryFieldVisuels(filter: {conditions: [
        {field: "field_image_libre", value: "1"}
      ]}) {
        entities {
          ... on MediaImage {
            name
            vignette
            publicUrl
          }
        }
      }
      fieldDateProduction {
        startPrecision
        startYear
        startMonth
        startDay
        sort
        endPrecision
        endYear
        endMonth
        endDay
        century
      }
      fieldOeuvreSiecle {
         entity {
          name
        }
      }
      fieldOeuvreTypesObjet {
        entity {
          name
          fieldLrefAdlib
        }
      }
      fieldDenominations {
        entity {
          name
        }
      }
      fieldMateriauxTechnique{
        entity {
          name
        }
      }
      fieldOeuvreDimensions {
        entity {
          fieldDimensionPartie {
            entity {
              name
            }
          }
          fieldDimensionType {
            entity {
              name
            }
          }
          fieldDimensionValeur
          fieldDimensionUnite {
           entity {
              name
            }
          }
        }
      }
      fieldOeuvreInscriptions{
        entity {
          fieldInscriptionType {
            entity {
              name
            }
          }
          fieldInscriptionMarque {
            value
          }
          fieldInscriptionEcriture {
            entity {
              name
            }
          }
        }
      }
      fieldOeuvreDescriptionIcono {
        value
      }
      fieldCommentaireHistorique {
        value

      }
      fieldOeuvreThemeRepresente	 {
        entity {
          name
        }
      }
      fieldLieuxConcernes {
        entity {
          name
        }
      }
      fieldModaliteAcquisition {
        entity {
          name
        }
      }
      fieldDonateurs {
        entity {
          name
        }
      }
      fieldDateAcquisition {
        startPrecision
        startYear
        startMonth
        startDay
        sort
        endPrecision
        endYear
        endMonth
        endDay
        century
      }
      fieldOeuvreNumInventaire
      fieldOeuvreStyleMouvement {
        entity {
          name
        }
      }
      fieldMusee {
        entity {
          name
        }
      }
      fieldOeuvreExpose {
        entity {
          name
        }
      }
      fieldOeuvreAudios {
        entity {
          fieldMediaFile {
            entity {
              url
              uri {
                value
                url
              }
            }
          }
        }
      }
      fieldOeuvreVideos {
        entity {
          fieldMediaVideoEmbedField
        }
      }
      fieldHdVisuel {
        entity {
          fieldMediaImage {
            entity {
              url
            }
          }
        }
      }
    }}
  }
}
""" 
  response = requests.post(url, json={'query': query}, headers=headers)

  data=response.json()
  with open(id+".json", 'w') as outfile:
    json.dump(data, outfile)

