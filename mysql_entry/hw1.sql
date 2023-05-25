-- (nome, cognome, email, password, image, username)

CREATE TABLE hw1_users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    username VARCHAR(50) NOT NULL UNIQUE
);
-- hw1_playlists (playlistDeezerId, playlistUser, date, url_yt)
CREATE TABLE hw1_playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlistDeezerId VARCHAR(2000) NOT NULL,
    playlistUser INT NOT NULL,
    date DATETIME DEFAULT now(),
    FOREIGN KEY (playlistUser) REFERENCES hw1_users(id)
);

-- hw1_playlistlike (userId, playlistId)
CREATE TABLE hw1_playlistlike (
    userId INT,
    playlistId INT,
    PRIMARY KEY (userId, playlistId),
    FOREIGN KEY (userId) REFERENCES hw1_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (playlistId) REFERENCES hw1_playlists(id) ON DELETE CASCADE ON UPDATE CASCADE
);
-- hw1_follow (followerId, followed)
CREATE TABLE hw1_follow (
    followerId INT,
    followed INT,
    PRIMARY KEY (followerId, followed),
    FOREIGN KEY (followerId) REFERENCES hw1_users (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (followed) REFERENCES hw1_users (id) ON DELETE CASCADE ON UPDATE CASCADE
);