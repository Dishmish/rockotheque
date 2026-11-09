ALTER TABLE reviews
    ADD COLUMN user_id INT UNSIGNED NULL AFTER album_id,
    ADD CONSTRAINT fk_reviews_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE;