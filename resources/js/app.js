// resources/js/app.js
import "./bootstrap";

import Alpine from "alpinejs";
import mask from "@alpinejs/mask";

Alpine.plugin(mask);
window.Alpine = Alpine;
Alpine.start();

// --- Flatpickr (NPM) ---
import flatpickr from "flatpickr";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";
import "flatpickr/dist/flatpickr.css";

// Localiza para PT-BR e expõe no window para os scripts Blade/Alpine
flatpickr.localize(Portuguese);
window.flatpickr = flatpickr;
