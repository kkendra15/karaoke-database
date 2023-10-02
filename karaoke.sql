-- Kendra Ferguson Z1933361
-- Victoria Spejcher Z1979694
-- Christian Beck Rygiewicz Z1968850
-- Cameron Haines Z1977114
-- Evan Koslofski Z1945612

-- Karaoke Database


-- Contributor for each database
CREATE TABLE IF NOT EXISTS contributor (
  id        INT AUTO_INCREMENT PRIMARY KEY, -- contributor identifier
  lastName  CHAR(64),                       -- contributor's last name
  firstName CHAR(64),                       -- contributor's first name
  stageName CHAR(64) NOT NULL               -- contributor's stage name, if applicable
);

CREATE TABLE IF NOT EXISTS band (           
  id    INT AUTO_INCREMENT PRIMARY KEY,     -- band identifier
  name  CHAR(64) NOT NULL                   -- band's name
);

CREATE TABLE IF NOT EXISTS song (
  id    INT AUTO_INCREMENT PRIMARY KEY,     -- song identifier
  title CHAR(128) NOT NULL,                 -- song title
  genre CHAR(64) NOT NULL                   -- song's genre
);

CREATE TABLE IF NOT EXISTS created (
  bandID    INT NOT NULL,                   -- band's id   
  songID    INT NOT NULL,                   -- song's id

  PRIMARY KEY (bandID, songID),
  FOREIGN KEY (bandID) REFERENCES band(id),
  FOREIGN KEY (songID) REFERENCES song(id)
);

CREATE TABLE IF NOT EXISTS contributedTo (  
  contributorID   INT NOT NULL,             -- contributor's id
  songID          INT NOT NULL,             -- song's id
  contributorRole CHAR(64) NOT NULL,        -- contributor's role in song

  PRIMARY KEY (contributorID, songID, contributorRole),
  FOREIGN KEY (contributorID) REFERENCES contributor(id),
  FOREIGN KEY (songID) REFERENCES song(id)
);

CREATE TABLE IF NOT EXISTS karaokeFile (
  id          INT AUTO_INCREMENT PRIMARY KEY, -- file identifier
  fileType    CHAR(1) NOT NULL                -- type of file, i.e solo, duet, etc
);

CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,          -- user identifier
  lastName  CHAR(64) NOT NULL,                -- user's last name
  firstName CHAR(64) NOT NULL                 -- user's first name
);

CREATE TABLE IF NOT EXISTS songQueue (        -- hold's the song queues
  fileID      INT NOT NULL,                   -- karaoke file's id (version e.g solo, duet)
  accountID   INT NOT NULL,                   -- user's id
  queueTime   TIMESTAMP NOT NULL,             -- time user signed up
  amount      DECIMAL(4,2),                   -- amount paid for priority queue 

  PRIMARY KEY (fileID, accountID),
  FOREIGN KEY (fileID) REFERENCES karaokeFile(id),
  FOREIGN KEY (accountID) REFERENCES user(id)
);

CREATE TABLE IF NOT EXISTS isVersionOf (      -- hold's each song's available karaoke file versions
  fileID  INT NOT NULL,                       -- file version id
  songID  INT NOT NULL,                       -- song's id

  PRIMARY KEY (fileID, songID),
  FOREIGN KEY (fileID) REFERENCES karaokeFile(id),
  FOREIGN KEY (songID) REFERENCES song(id)
);

