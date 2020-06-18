USE mytodo;

CREATE TABLE tasks (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    completed BOOLEAN NOT NULL);
