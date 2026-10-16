import json


class Response:
    def __init__(self, body, status=200, headers=None): # attention, jamais header={}
        self.headers = headers or {}
        self.body = body
        self.status = status

    @staticmethod #sert à écrire une méthode qu'on appelle sur la classe elle-même, sans avoir créé d'objet avant.
    def html(body, status=200):
        return Response(body, status, {"Content-Type": "text/html; charset=utf-8"})

    @staticmethod
    def json(data, status=200):
        return Response(json.dumps(data), status, {"Content-Type": "application/json; charset=utf-8"})

    @staticmethod
    def redirect(url, status=302):
        return Response("", status, {"Location": url})
