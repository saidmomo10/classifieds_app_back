-- INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
-- ('role-list', 'web', NOW(), NOW()),
-- ('role-create', 'web', NOW(), NOW()),
-- ('role-edit', 'web', NOW(), NOW()),
-- ('role-delete', 'web', NOW(), NOW());


-- Insérer des catégories
INSERT INTO categories (name, created_at, updated_at) VALUES
('Annonces business', NOW(), NOW());

-- Insérer des sous-catégories
INSERT INTO sub_categories (name, icone, category_id, created_at, updated_at) VALUES
('Meubles', 'icones/canap.png', 1, NOW(), NOW()),
('Voiture', 'icones/car.png', 1, NOW(), NOW()),
('Education/Formation', 'icones/education.png', 1, NOW(), NOW()),
('Bar/Fast-food', 'icones/food.jpg', 1, NOW(), NOW()),
('Santé', 'icones/health.png', 1, NOW(), NOW()),
('Immobilier', 'icones/house.png', 1, NOW(), NOW()),
('Produits vivriers', 'icones/market.jpg', 1, NOW(), NOW()),
('Téléphone', 'icones/phone.jpg', 1, NOW(), NOW()),
('Mode et vêtement', 'icones/vêtements.jpg', 1, NOW(), NOW()),
('Appareil électronique', 'icones/canap.png', 1, NOW(), NOW()),
('Appareil électroménager', 'icones/electro.png', 1, NOW(), NOW());



INSERT INTO subscriptions (name, price, max_images, max_ads, duration, type, description, created_at, updated_at) VALUES
('Free', 0, 2, 2, 20, 'Gratuit', 'gratuit', NOW(), NOW());


-- https://web-production-85753.up.railway.app/https://classifiedsappback-production.up.railway.app/api/

DO $$
DECLARE
    user_id BIGINT;
    role_id BIGINT;
BEGIN
    -- Insérer un nouvel utilisateur et récupérer son ID
    INSERT INTO users (name, email, password, created_at, updated_at) 
    VALUES 
        ('Bouhari', 'bouhari.saidou@ecole229.bj', '$2y$10$E1GkN8xCgH3U3ZPqg8MtiO98WjIc3z2.L5Yt43tqdxBv4xJFXgDO2', NOW(), NOW())
    RETURNING id INTO user_id;

    -- Insérer un nouveau rôle et récupérer son ID
    INSERT INTO roles (name, guard_name, created_at, updated_at) 
    VALUES 
        ('Admin', 'web', NOW(), NOW())
    RETURNING id INTO role_id;

    -- Associer toutes les permissions à ce rôle
    INSERT INTO role_has_permissions (role_id, permission_id) 
    SELECT role_id, id FROM permissions;

    -- Associer le rôle à l'utilisateur
    INSERT INTO model_has_roles (role_id, model_type, model_id) 
    VALUES (role_id, 'App\\Models\\User', user_id);
END $$;
