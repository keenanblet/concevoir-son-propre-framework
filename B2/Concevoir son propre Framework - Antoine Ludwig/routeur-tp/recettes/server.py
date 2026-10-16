from http.server import BaseHTTPRequestHandler

from request import Request
from routes import router


class Handler(BaseHTTPRequestHandler): #recoit les requêtes réseaux
    def handle_any(self): #fonction qui traite une requête
        # Temps 1 : construire la Request
        longueur = int(self.headers.get("Content-Length", 0)) # nb octet du body
        body = self.rfile.read(longueur).decode("utf-8") #self.rfile est le flux d'entrée, ce que le nav envoie.   .read(longueur) lit le nb doctet  .decode(...) transforme les octets en texte
        request = Request(self.command, self.path, body)

        # Temps 2 : produire une Response via le routeur
        response = router.resolve(request)

        # Temps 3 : envoyer la Response
        self.send_response(response.status)
        for nom, valeur in response.headers.items():
            self.send_header(nom, valeur)
        self.end_headers()
        self.wfile.write(response.body.encode("utf-8")) #flux de sortie

    do_GET = handle_any
    do_POST = handle_any
    do_DELETE = handle_any
