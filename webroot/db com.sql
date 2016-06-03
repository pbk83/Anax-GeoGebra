DROP TABLE comment;
DROP TABLE question_tag;
DROP TABLE answer;
DROP TABLE question;
DROP TABLE user;
DROP TABLE tag;

CREATE TABLE user(
  id   INTEGER PRIMARY KEY, 
  username  VARCHAR(20) UNIQUE NOT NULL,
	password 	VARCHAR(20) NOT NULL,
	name			VARCHAR(20) NOT NULL,
	email			VARCHAR(50) NOT NULL
);

CREATE TABLE question(
	id 	INTEGER PRIMARY KEY,
	question_text TEXT 	NOT NULL,
	question_heading TEXT NOT NULL,
	user_id 			INTEGER,
	FOREIGN KEY(user_id) REFERENCES user(id)
);

CREATE TABLE answer(
	id 	INTEGER PRIMARY KEY,
	answer_text TEXT NOT NULL,
	user_id 		INTEGER,
	question_id INTEGER,
	FOREIGN KEY(user_id) REFERENCES user(id),
	FOREIGN KEY(question_id) REFERENCES question(id)
);

CREATE TABLE tag(
	id 	INTEGER PRIMARY KEY,
	tag_text 		TEXT NOT NULL UNIQUE
);

CREATE TABLE question_tag(
	question_id INTEGER,
  tag_id INTEGER,
  FOREIGN KEY(question_id) REFERENCES question(id) ON DELETE CASCADE,
  FOREIGN KEY(tag_id) REFERENCES tag(id) ON DELETE CASCADE
);

CREATE TABLE comment(
	id 	INTEGER PRIMARY KEY,
	comment_text 	TEXT NOT NULL,
	user_id				INTEGER,
	question_id		INTEGER,
	answer_id			INTEGER,
	FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY(question_id) REFERENCES question(id) ON DELETE CASCADE,
	FOREIGN KEY(answer_id) REFERENCES answer(id) ON DELETE CASCADE
);

INSERT INTO user (username, password, name, email)  
VALUES ('user nr 1', 'hemligt', 'Förnamn Efternamn', 'patrik.blomqvist@passagen.se');

INSERT INTO user (username, password, name, email)  
VALUES ('user nr 2', 'hemligt', 'Förnamn Efternamn', 'patrik.blomqvist@passagen.se');

INSERT INTO question (question_text, user_id)  
VALUES ("Är det sant?", 1);

INSERT INTO question (question_text, user_id)  
VALUES ("Vad är det?", 1);

INSERT INTO question (question_text, user_id)  
VALUES ("Kan du det?", 2);

SELECT question_text, username FROM question INNER JOIN user
  ON user.user_id = question.user_id
	WHERE user.user_id = 1;
	
SELECT * FROM tag t  
  LEFT JOIN question_tag qt ON qt.tag_id = t.id  
  WHERE qt.question_id = '1';