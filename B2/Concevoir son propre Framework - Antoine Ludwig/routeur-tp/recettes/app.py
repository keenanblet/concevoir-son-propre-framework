from http.server import HTTPServer

from server import Handler


if __name__ == "__main__":
    print("Serveur sur http://localhost:8000")
    HTTPServer(("localhost", 8000), Handler).serve_forever() #ouvre le port 8000 et écoute (.serve_forever => boucle sans fin)
