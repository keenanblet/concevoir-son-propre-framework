from urllib.parse import urlparse, parse_qs


class Request:
    def __init__(self, method, url, body=""): #constructeur body vaut rien si il n'est pas renseigné

        self.method = method # on range la methode get dans l'objet
        parsed = urlparse(url) # découpe l'url en morceau
        self.path = parsed.path #on garde que le chemin sans le ?...
        self.query = parse_qs(parsed.query) #si query vaut q=pizza alors parse_qs la transforme en {'q': ['pizza']}
        self.form = parse_qs(body)

    def get(self, nom, defaut=""):
        valeurs = self.query.get(nom, []) #le get c'est le get natif de python
        if valeurs:
            return valeurs[0]
        else:
            return defaut

    def post(self, nom, defaut=""):
        valeurs = self.form.get(nom, [])
        if valeurs:
            return valeurs[0]
        else:
            return defaut
