// import 'mdb-ui-kit/css/mdb.min.css';
import 'mdb-ui-kit/js/mdb.umd.min.js';
// import './bootstrap.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js';
/*
* Welcome to your app's main JavaScript file!
*
* This file will be included onto the page via the importmap() Twig
* function, which should already be in your base.html.twig.
*/
// Importation de la feuille de style MDB

// Importation du bundle JavaScript MDB (déjà importé plus haut)
// import 'mdb-ui-kit/js/mdb.umd.min.js';

// Importation des composants nécessaires (optionnel si tout le bundle est importé)
import './styles/app.css';
// Initialization for ES Users
import { Carousel, Collapse, initMDB } from "mdb-ui-kit";
initMDB({ Carousel, Collapse });

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
