import re

from response import Response


class Router:
    def __init__(self):
        self.routes = {}

    # Méthode générique : enregistre une route pour n'importe quelle méthode HTTP.
    # Résultat dans self.routes : {"GET": {"/recettes": handler}, "POST": {...}}
    def add(self, method, path, handler):
        # On met la méthode en majuscules pour que "get" et "GET" soient la même entrée
        method = method.upper() # get -> GET, post -> POST, delete -> DELETE
        # setdefault : si self.routes n'a pas encore de clé "GET", il crée {} pour elle.
        # Dans tous les cas il renvoie le dictionnaire de cette méthode.
        # Sans ça, self.routes["GET"] provoquerait un KeyError la première fois.
        routes_de_la_methode = self.routes.setdefault(method, {})
        # On range le handler (la fonction, SANS parenthèses : on ne l'exécute pas)
        # sous son chemin. C'est resolve() qui l'appellera plus tard.
        routes_de_la_methode[path] = handler

    # Raccourci : router.get("/x", f) équivaut à router.add("GET", "/x", f)
    def get(self, path, handler):
        self.add("GET", path, handler)

    # Raccourci pour les créations (formulaires, envoi de données)
    def post(self, path, handler):
        self.add("POST", path, handler)

    # Raccourci pour les suppressions
    def delete(self, path, handler):
        self.add("DELETE", path, handler)

    # Transforme un pattern en regex : /recettes/{id} -> ^/recettes/(?P<id>[^/]+)$
    # {id} devient un groupe nommé qui capture tout sauf un "/".
    # ^ et $ forcent le chemin ENTIER à correspondre (sinon /recettes/42/x matcherait).
    def _vers_regex(self, pattern):
        return "^" + re.sub(r"\{(\w+)\}", r"(?P<\1>[^/]+)", pattern) + "$"

    def resolve(self, request):
        # Les routes sont testées DANS L'ORDRE où elles ont été déclarées,
        # et la première qui correspond gagne.
        for pattern, handler in self.routes.get(request.method, {}).items():
            m = re.match(self._vers_regex(pattern), request.path)
            if m:
                params = m.groupdict()          # {"id": "42"}
                return handler(request, params)

        # Rien ne correspond pour cette méthode.
        # On regarde si l'URL existe pour une AUTRE méthode : si oui c'est un 405.
        for methode, routes in self.routes.items():
            if methode == request.method:
                continue
            for pattern in routes:
                if re.match(self._vers_regex(pattern), request.path):
                    return Response.html("<h1>405 Méthode non autorisée</h1>", 405)

        # L'URL n'existe pour aucune méthode : 404
        return Response.html("<h1>404 Page non trouvée</h1>", 404)
