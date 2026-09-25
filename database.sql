DROP DATABASE IF EXISTS rockotheque;

CREATE DATABASE rockotheque
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE rockotheque;

CREATE TABLE genres (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);
CREATE TABLE artists (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    country VARCHAR(100),
    formation_year SMALLINT UNSIGNED,
    biography TEXT
);
CREATE TABLE albums (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    genre_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    release_year SMALLINT UNSIGNED NOT NULL,
    cover_image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_albums_genre
        FOREIGN KEY (genre_id)
        REFERENCES genres(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
CREATE TABLE album_artists (
    album_id INT UNSIGNED NOT NULL,
    artist_id INT UNSIGNED NOT NULL,
    role VARCHAR(100) DEFAULT 'Artiste principal',

    PRIMARY KEY (album_id, artist_id),

    CONSTRAINT fk_album_artists_album
        FOREIGN KEY (album_id)
        REFERENCES albums(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_album_artists_artist
        FOREIGN KEY (artist_id)
        REFERENCES artists(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    album_id INT UNSIGNED NOT NULL,
    reviewer_name VARCHAR(100) NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_reviews_rating
        CHECK (rating BETWEEN 1 AND 5),

    CONSTRAINT fk_reviews_album
        FOREIGN KEY (album_id)
        REFERENCES albums(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
INSERT INTO genres (name, description) VALUES
('Rock', 'Musique rock classique caractérisée par les guitares électriques.'),
('Hard Rock', 'Style de rock puissant avec des guitares fortement amplifiées.'),
('Rock progressif', 'Genre utilisant des compositions longues et complexes.');

INSERT INTO artists (name, country, formation_year, biography) VALUES
(
    'Dire Straits',
    'Royaume-Uni',
    1977,
    'Groupe britannique fondé à Londres par Mark Knopfler.'
),
(
    'Deep Purple',
    'Royaume-Uni',
    1968,
    'Groupe britannique considéré comme un pionnier du hard rock.'
),
(
    'King Crimson',
    'Royaume-Uni',
    1968,
    'Groupe britannique majeur du rock progressif.'
),
(
    'Pink Floyd',
    'Royaume-Uni',
    1965,
    'Groupe britannique reconnu pour ses albums conceptuels.'
);

INSERT INTO albums (
    genre_id,
    title,
    release_year,
    cover_image,
    description
) VALUES
(
    1,
    'Brothers in Arms',
    1985,
    NULL,
    'Un album emblématique de Dire Straits comprenant la chanson Brothers in Arms.'
),
(
    2,
    'Machine Head',
    1972,
    NULL,
    'Un album classique de Deep Purple comprenant Smoke on the Water.'
),
(
    3,
    'In the Court of the Crimson King',
    1969,
    NULL,
    'Un album fondateur du rock progressif.'
),
(
    3,
    'The Dark Side of the Moon',
    1973,
    NULL,
    'Un album conceptuel célèbre de Pink Floyd.'
);

INSERT INTO album_artists (album_id, artist_id, role) VALUES
(1, 1, 'Artiste principal'),
(2, 2, 'Artiste principal'),
(3, 3, 'Artiste principal'),
(4, 4, 'Artiste principal');

INSERT INTO reviews (
    album_id,
    reviewer_name,
    rating,
    comment
) VALUES
(
    1,
    'Dmitriy',
    5,
    'Un album profond avec une excellente qualité sonore.'
),
(
    2,
    'Dmitriy',
    5,
    'Un grand classique du hard rock.'
),
(
    3,
    'Dmitriy',
    4,
    'Une œuvre complexe et originale.'
),
(
    4,
    'Dmitriy',
    5,
    'Un album atmosphérique qui reste intemporel.'
);