// frontend/src/utils/traduire.js
import { dictionnaire } from "../assets/dictionnaire";

export function traduire(texte, langue = "mg") {
  if (langue === "mg") {
    return dictionnaire[texte] || texte;
  }
  return texte; // FR par défaut
}
