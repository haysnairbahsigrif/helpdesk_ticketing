-- Solusi akhir dan admin yang menyelesaikan tiket.
ALTER TABLE tickets
    ADD COLUMN solusi TEXT NULL AFTER keluhan,
    ADD COLUMN completed_by INT UNSIGNED NULL AFTER completed_at,
    ADD CONSTRAINT fk_tickets_completed_by
        FOREIGN KEY (completed_by)
        REFERENCES users (id)
        ON DELETE SET NULL;

-- Catatan internal: satu tiket dapat memiliki banyak catatan.
CREATE TABLE ticket_notes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ticket_id INT UNSIGNED NOT NULL,
    admin_id INT UNSIGNED NULL,
    catatan TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_ticket_notes_ticket_created (ticket_id, created_at, id),

    CONSTRAINT fk_ticket_notes_ticket
        FOREIGN KEY (ticket_id)
        REFERENCES tickets (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_ticket_notes_admin
        FOREIGN KEY (admin_id)
        REFERENCES users (id)
        ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;