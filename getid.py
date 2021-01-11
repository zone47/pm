#script pour récupérer une liste de notice par type
import requests
import json
import os

os.chdir("E:/WD/parismusee")
fic = open("r.txt", "a")

url = 'http://apicollections.parismusees.paris.fr/graphql'
headers={'auth-token': '****', 'Content-Type': 'application/json'}
i = 0
while i < 14:
  offset=str(i*500)
  i=i+1
  query = """
  {
    nodeQuery(filter: {conditions: [
    {field: \"type\", value: \"oeuvre\"}
    {field: \"field_oeuvre_types_objet.entity.field_lref_adlib\", value: \"4284\"}
    ]}, limit:500, offset:"""+offset+""") {
    count
    entities {
      ... on NodeOeuvre {
      nid
      }
    }
    }
  }
  """

  response = requests.post(url, json={'query': query}, headers=headers)

  data=response.json()

  for p in data['data']['nodeQuery']['entities']:
    id='null'
    if p is not None:
      for key, row in p.items():
        id=row
    print id
    fic.write(str(id)+"\n")
