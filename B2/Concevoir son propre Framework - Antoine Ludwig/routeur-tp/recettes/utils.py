from html import escape

from donnees import recettes


# Cherche une recette par son id. L'id de l'URL est du TEXTE ("2") alors que
# celui de la liste est un NOMBRE (2) : on compare donc en texte avec str().
# Avantage : "abc" ne plante pas (pas de int("abc")), il ne trouve juste rien.
def trouver_recette(id_texte):
    for recette in recettes:
        if str(recette["id"]) == id_texte:
            return recette
    return None


# Construit la liste HTML <ul> d'une liste de recettes.
# escape() transforme < > & " ' en entités (&lt; &gt;...) : le navigateur les
# AFFICHE comme du texte au lieu de les exécuter. C'est la protection XSS.
def liste_html(liste):
    items = ""
    for r in liste:
        items += f'<li><a href="/recettes/{r["id"]}">{escape(r["titre"])}</a> ({r["duree"]} min)</li>'
    return f"<ul>{items}</ul>"
