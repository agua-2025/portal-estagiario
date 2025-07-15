import "./bootstrap";

import Alpine from "alpinejs";
import mask from "@alpinejs/mask"; // <-- 1. Importe o plugin

Alpine.plugin(mask); // <-- 2. Registre o plugin no Alpine

window.Alpine = Alpine;

Alpine.start();
