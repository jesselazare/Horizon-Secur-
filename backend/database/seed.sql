-- Donnees de test

INSERT INTO voyage (id, destination, date_depart, prix_par_personne, capacite_max, titre, pays, description, image_url) VALUES
(1, 'Nairobi', '2026-08-15', 1299.00, 20, 'Safari au Kenya', 'Kenya', 'Decouverte des savanes et de la faune africaine.', '/images/kenya.jpg'),
(2, 'Denpasar', '2026-09-01', 899.00, 30, 'Escapade a Bali', 'Indonesie', 'Plages paradisiaques et temples.', '/images/bali.jpg'),
(3, 'Lisbonne', '2026-07-20', 450.00, 40, 'City break a Lisbonne', 'Portugal', 'Week-end culturel et gastronomie portugaise.', '/images/lisbonne.jpg'),
(4, 'Reykjavik', '2026-10-05', 1599.00, 15, 'Aventure en Islande', 'Islande', 'Aurores boreales et geysers.', '/images/islande.jpg'),
(5, 'Male', '2026-11-12', 2200.00, 10, 'Detente aux Maldives', 'Maldives', 'Sejour tout inclus sur lagon turquoise.', '/images/maldives.jpg');

INSERT INTO agent_interne (id, nom, prenom, email, password, statut) VALUES
(1, 'Fokam', 'Milly', 'admin@horizon-secur.fr', '$2y$10$v5NOJY3TjvgabNmQAFRBOuPeq33zU/K9WILNkELM/cUwSfhxM.2ES', 'actif'),
(2, 'Martin', 'Sophie', 'agent@horizon-secur.fr', '$2y$10$v5NOJY3TjvgabNmQAFRBOuPeq33zU/K9WILNkELM/cUwSfhxM.2ES', 'actif');