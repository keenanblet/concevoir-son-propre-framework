import random
from html import escape

from response import Response
from router import Router
from donnees import recettes
from utils import trouver_recette, liste_html

router = Router()


# GET / : page d'accueil avec un lien vers /recettes
def accueil(request, params):
    return Response.html('<h1>Catalogue de recettes</h1><a href="/recettes">Voir les recettes</a>')

router.get("/", accueil)


# GET /recettes : la liste des 5 recettes
def liste_recettes(request, params):
    return Response.html("<h1>Recettes</h1>" + liste_html(recettes))

router.get("/recettes", liste_recettes)


# GET /recettes/surprise : une recette au hasard en JSON
def recette_surprise(request, params):
    return Response.json(random.choice(recettes))

# ATTENTION À L'ORDRE : /recettes/surprise doit être déclarée AVANT /recettes/{id}.
# Le routeur teste les routes dans l'ordre de déclaration et s'arrête à la première
# qui correspond. Or {id} accepte n'importe quel texte, donc "surprise" aussi :
# si /recettes/{id} passait en premier, GET /recettes/surprise serait capturée avec
# id="surprise" et renverrait un 404 "recette introuvable". La route précise doit
# donc toujours précéder la route générique.
router.get("/recettes/surprise", recette_surprise)


# GET /recettes/{id} : le détail d'une recette, 404 si l'id n'existe pas
def detail_recette(request, params):
    recette = trouver_recette(params["id"])
    if recette is None:
        # params["id"] vient de l'URL donc de l'utilisateur : on l'échappe (XSS)
        return Response.html(f"<h1>Recette {escape(params['id'])} introuvable</h1>", 404)
    return Response.html(
        f"<h1>{escape(recette['titre'])}</h1>"
        f"<p>Durée : {recette['duree']} min</p>"
        '<a href="/recettes">Retour à la liste</a>'
    )

router.get("/recettes/{id}", detail_recette)


# GET /recherche?q=mot : cherche dans les titres, tout afficher si q est vide
def recherche(request, params):
    q = request.get("q")  # "" si le paramètre est absent ou vide
    if q:
        resultats = [r for r in recettes if q.lower() in r["titre"].lower()]
    else:
        resultats = recettes
    # q vient de l'utilisateur : on l'échappe avant de l'afficher (XSS)
    return Response.html(f"<h1>Résultats pour « {escape(q)} »</h1>" + liste_html(resultats))

router.get("/recherche", recherche)


# POST /recettes : reçoit titre et durée, renvoie un JSON de confirmation (201)
def creer_recette(request, params):
    titre = request.post("titre").strip()
    duree = request.post("duree")
    if not titre:
        return Response.json({"error": "Le titre est obligatoire"}, 400)
    return Response.json({"created": True, "titre": titre, "duree": duree}, 201)

router.post("/recettes", creer_recette)


# DELETE /recettes/{id} : renvoie {"deleted": true, "id": "..."} avec le code 200
def supprimer_recette(request, params):
    return Response.json({"deleted": True, "id": params["id"]})

router.delete("/recettes/{id}", supprimer_recette)
