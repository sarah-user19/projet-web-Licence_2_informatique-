let mesVotes = {};

window.onload = function () {
    fetch("get_mes_votes.php")
        .then(r => r.json())
        .then(votes => {
            mesVotes = votes;
            chargerHistoires();
        });
};

function appliquerEtatBoutons(boutonVrai, boutonFaux, voted) {
    if (!boutonVrai || !boutonFaux) return;
    if (voted === "vrai") {
        boutonVrai.disabled = true;
        boutonVrai.title    = "Votre vote actuel";
        boutonFaux.disabled = false;
        boutonFaux.title    = "Changer d'avis";
    } else if (voted === "faux") {
        boutonFaux.disabled = true;
        boutonFaux.title    = "Votre vote actuel";
        boutonVrai.disabled = false;
        boutonVrai.title    = "Changer d'avis";
    } else {
        boutonVrai.disabled = false;
        boutonFaux.disabled = false;
        boutonVrai.title    = "";
        boutonFaux.title    = "";
    }
}

function chargerHistoires() {
    fetch("get_histoires.php")
        .then(r => r.json())
        .then(data => {
            let zone = document.getElementById("histoires");
            if (!zone) return;
            zone.innerHTML = "";

            data.forEach(histoire => {
                let div = document.createElement("div");
                div.classList.add("histoire");

                let total       = histoire.vrai + histoire.faux;
                let pourcentage = total > 0 ? Math.round((histoire.vrai / total) * 100) : 0;

                div.innerHTML = `
                    <img src="${histoire.img}" class="image-produit" alt="${histoire.titre}">
                    <h2>${histoire.titre}</h2>
                    <pre class="story-texte">${histoire.story}</pre>
                    <p class="auteur">Publié par : <strong>${histoire.nom}</strong></p>
                    <div class="barre-container">
                        <div class="barre" style="width: ${pourcentage}%"></div>
                    </div>
                    <p class="veracite">Véracité : ${pourcentage}%</p>
                    <button onclick="voterHistoire(${histoire.id}, 'vrai')">👍 ${histoire.vrai}</button>
                    <button onclick="voterHistoire(${histoire.id}, 'faux')">👎 ${histoire.faux}</button>
                    <h3>Commentaires</h3>
                    <div id="commentaires-${histoire.id}"></div>
                    <textarea id="input-${histoire.id}" placeholder="Ajouter un avis..."></textarea>
                    <button onclick="envoyerCommentaire(${histoire.id})">Envoyer</button>
                `;

                zone.appendChild(div);

                let bVrai = div.querySelector(`button[onclick="voterHistoire(${histoire.id}, 'vrai')"]`);
                let bFaux = div.querySelector(`button[onclick="voterHistoire(${histoire.id}, 'faux')"]`);
                appliquerEtatBoutons(bVrai, bFaux, mesVotes["histoire_" + histoire.id]);

                chargerCommentaires(histoire.id);
            });
        });
}

function chargerCommentaires(histoire_id) {
    fetch("get_commentaires.php?histoire_id=" + histoire_id)
        .then(r => r.json())
        .then(data => {
            let zone = document.getElementById("commentaires-" + histoire_id);
            if (!zone) return;
            zone.innerHTML = "";

            if (data.length === 0) {
                zone.innerHTML = '<p style="color:#9e8c7a;font-size:0.85rem;">Aucun commentaire pour l\'instant.</p>';
                return;
            }

            let counter = document.getElementById("comments-count");
            if (counter) counter.textContent = data.length;

            data.forEach(commentaire => {
                let div = document.createElement("div");
                div.classList.add("commentaire");
                div.dataset.id = commentaire.id;

                let total       = commentaire.vrai + commentaire.faux;
                let pourcentage = total > 0 ? Math.round((commentaire.vrai / total) * 100) : 0;

                let auteur    = commentaire.auteur || "Anonyme";
                let initiales = auteur.substring(0, 2).toUpperCase();

                div.innerHTML = `
                    <div class="commentaire-header">
                        <div class="commentaire-avatar">${initiales}</div>
                        <span class="commentaire-nom">${auteur}</span>
                    </div>
                    <pre style="font-family:inherit; font-size:0.82rem; white-space:pre-wrap; margin:2px 0;">${commentaire.texte}</pre>
                    <div class="barre-container" style="max-width:300px;">
                        <div class="barre" style="width: ${pourcentage}%"></div>
                    </div>
                    <p class="veracite-com">Véracité : ${pourcentage}%</p>
                    <button class="btn-vrai" onclick="voter('${commentaire.id}', 'vrai')">👍 ${commentaire.vrai}</button>
                    <button class="btn-faux" onclick="voter('${commentaire.id}', 'faux')">👎 ${commentaire.faux}</button>
                `;

                zone.appendChild(div);

                let bVrai = div.querySelector(".btn-vrai");
                let bFaux = div.querySelector(".btn-faux");
                appliquerEtatBoutons(bVrai, bFaux, mesVotes["commentaire_" + commentaire.id]);
            });
        });
}

function envoyerCommentaire(histoire_id) {
    let texte = document.getElementById("input-" + histoire_id).value;
    if (texte.trim() === "") { alert("Écris quelque chose !"); return; }

    fetch("ajouter_commentaire.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ texte: texte, histoire_id: histoire_id })
    }).then(() => {
        document.getElementById("input-" + histoire_id).value = "";
        chargerCommentaires(histoire_id);
    });
}

function voter(id, type) {
    fetch("vote.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id, type: type })
    })
    .then(r => r.json())
    .then(reponse => {
        if (reponse.status === "deja_vote") return;

        mesVotes["commentaire_" + id] = type;

        let div   = document.querySelector(`.commentaire[data-id="${id}"]`);
        if (!div) return;

        let bVrai = div.querySelector(".btn-vrai");
        let bFaux = div.querySelector(".btn-faux");
        let barre = div.querySelector(".barre");
        let label = div.querySelector(".veracite-com");

        let vrai = reponse.vrai;
        let faux = reponse.faux;

        let total       = vrai + faux;
        let pourcentage = total > 0 ? Math.round((vrai / total) * 100) : 0;

        bVrai.textContent = "👍 " + vrai;
        bFaux.textContent = "👎 " + faux;
        if (barre) barre.style.width = pourcentage + "%";
        if (label) label.textContent = "Véracité : " + pourcentage + "%";

        appliquerEtatBoutons(bVrai, bFaux, type);
    });
}

function voterHistoire(id, type) {
    fetch("vote_histoire.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id, type: type })
    })
    .then(r => r.json())
    .then(reponse => {
        if (reponse.status === "deja_vote") return;

        mesVotes["histoire_" + id] = type;

        let boutonVrai = document.querySelector(`button[onclick="voterHistoire(${id}, 'vrai')"]`);
        let boutonFaux = document.querySelector(`button[onclick="voterHistoire(${id}, 'faux')"]`);
        if (!boutonVrai || !boutonFaux) return;

        let vrai = reponse.vrai;
        let faux = reponse.faux;
        let total = vrai + faux;
        let pourcentage = total > 0 ? Math.round((vrai / total) * 100) : 0;

        // Cherche dans tout le document les éléments liés à cet objet
        let barre    = document.querySelector(".barre-container .barre");
        let veracite = document.querySelector(".veracite");

        if (barre)    barre.style.width = pourcentage + "%";
        if (veracite) veracite.textContent = "Véracité : " + pourcentage + "%";

        boutonVrai.innerHTML = '<img style="height:20px;" src="image/like.png" alt=""> ' + vrai;
        boutonFaux.innerHTML = '<img style="height:20px;" src="image/dislike.png" alt=""> ' + faux;

        appliquerEtatBoutons(boutonVrai, boutonFaux, type);
    });
}
function envoyerHistoire() {
    let texte      = document.getElementById("texte-histoire").value;
    let imageInput = document.getElementById("image");

    if (texte.trim() === "") { alert("Écris une histoire !"); return; }
    if (imageInput.files.length === 0) { alert("Ajoute une image !"); return; }

    let formData = new FormData();
    formData.append("texte", texte);
    formData.append("image", imageInput.files[0]);

    fetch("ajouter_histoire.php", { method: "POST", body: formData })
    .then(r => r.text())
    .then(reponse => {
        if (reponse === "ok") {
            document.getElementById("texte-histoire").value = "";
            imageInput.value = "";
            chargerHistoires();
        } else {
            alert("Erreur : " + reponse);
        }
    });
}