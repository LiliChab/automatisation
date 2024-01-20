from flask import Flask, jsonify
import requests
import time

app = Flask(__name__)

# Fonction pour récupérer les données de l'API server
def get_api_data():
    max_retries = 10  # Nombre maximal de tentatives
    retry_delay = 2  # Délai entre les tentatives en secondes

    for _ in range(max_retries):
        try:
            # Faites une requête GET à l'API server
            response = requests.get(api_url)

            # Si la requête a réussi (code 200), retournez les données JSON
            if response.status_code == 200:
                return response.json()
            else:
                print(f"Erreur lors de la requête à l'API server. Code d'erreur: {response.status_code}")

        except requests.ConnectionError:
            print("Connexion à l'API server impossible. Réessai dans {retry_delay} secondes.")
            time.sleep(retry_delay)

    print(f"Impossible de récupérer les données après {max_retries} tentatives.")
    return None

# URL de l'API server
api_url = "http://backend:80/api/data"

# Récupérez les données de l'API server
api_data = get_api_data()

# Si les données ont été récupérées avec succès
if api_data:
    # Récupérez les coefficients et les notes
    coefficients = api_data['coefficients']
    toutes_les_notes = api_data['toutes_les_notes']

    # Effectuez le calcul de la moyenne pondérée
    moyennes_ponderees = []

    for notes_etudiant in toutes_les_notes:
        # Vérifiez que la longueur des coefficients correspond à la longueur des notes
        if len(coefficients) != len(notes_etudiant):
            print("Erreur: la longueur des coefficients ne correspond pas à la longueur des notes.")
            break

        # Calculez la moyenne pondérée pour chaque étudiant
        moyenne_ponderee = sum(c * n for c, n in zip(coefficients, notes_etudiant)) / sum(coefficients)
        moyennes_ponderees.append(moyenne_ponderee)

# Ajoutez un point de terminaison pour récupérer les moyennes
@app.route('/api/moyennes', methods=['GET'])
def get_moyennes():
    return jsonify({'moyennes': moyennes_ponderees})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=82)
