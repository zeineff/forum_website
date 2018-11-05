CREATE DATABASE forum;
USE forum;

CREATE TABLE users (
	id int NOT NULL AUTO_INCREMENT,
	username varchar(64) NOT NULL UNIQUE,
	password varchar(128) NOT NULL,
	email varchar(128) NOT NULL UNIQUE,
	avatar varchar(64) DEFAULT "default.png",
	PRIMARY KEY (id)
);

CREATE TABLE threads (
	id int NOT NULL AUTO_INCREMENT,
	user_id int NOT NULL,
	date_created timestamp NOT NULL,
	title varchar(256) NOT NULL UNIQUE,
	comments boolean NOT NULL DEFAULT true,
	PRIMARY KEY (id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
	id int NOT NULL AUTO_INCREMENT,
	thread_id int NOT NULL,
	user_id int NOT NULL,
	date_created timestamp NOT NULL,
	content text,
	PRIMARY KEY (id),
	FOREIGN KEY (thread_id) REFERENCES threads(id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tags (
	id int NOT NULL AUTO_INCREMENT,
	tag varchar(64) NOT NULL UNIQUE,
	PRIMARY KEY (id)
);

CREATE TABLE thread_tags (
	thread_id int NOT NULL,
	tag_id int NOT NULL,
	PRIMARY KEY (thread_id, tag_id),
	FOREIGN KEY (thread_id) REFERENCES threads(id),
	FOREIGN KEY (tag_id) REFERENCES tags(id)
);

CREATE TABLE thread_likes (
	thread_id int NOT NULL,
	user_id int NOT NULL,
	PRIMARY KEY (thread_id, user_id),
	FOREIGN KEY (thread_id) REFERENCES threads(id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comment_likes (
	comment_id int NOT NULL,
	user_id int NOT NULL,
	PRIMARY KEY (comment_id, user_id),
	FOREIGN KEY (comment_id) REFERENCES comments(id),
	FOREIGN KEY (user_id) REFERENCES users(id)
);



INSERT INTO users (username, password, email, avatar) VALUES
	("Spyro", "$2y$10$FJ484BLrEl5PdnW.rA.2weWhZl2R9mGCocIWr5qmsfEscyMLuepha", "spyro@website.com", "141592653.png"),
	("Sonic", "$2y$10$ED4mZePZjkarJlTf/0ykP..bDiePj6AtlQJHsuwCqvBrrjokmvi4G", "sonic@website.com", "589793238.png"),
	("Rayman", "$2y$10$cy51cd7tWbq3c4MU2EG5PeCVCwg1.OOEH5IMwkzLa3zXbfbEinGjK", "rayman@website.com", "462643383.png")
;

INSERT INTO threads (user_id, title, comments) VALUES
	(1, "Lorem ipsum", true),
	(2, "Dolor sit amet", true),
	(3, "No comments allowed", false)
;

INSERT INTO comments (thread_id, user_id, content) VALUES
	(1, 1, "Sed ut perspiciatis unde omnis"),
	(2, 2, "Iste natus error sit voluptatem accusantium doloremque laudantium"),
	(3, 3, "No comments"),
	
	(1, 1, "Totam rem aperiam, eaque ipsa"),
	(1, 2, "Quae ab illo inventore veritatis et quasi"),
	(1, 1, "Architecto beatae vitae dicta sunt explicabo"),
	
	(2, 2, "Nemo enim ipsam voluptatem quia"),
	(2, 2, "Voluptas sit aspernatur aut odit aut fugit"),
	(2, 3, "Sed quia consequuntur magni dolores"),
	(2, 1, "Eos qui ratione voluptatem sequi nesciunt"),
	(2, 3, "Neque porro"),
	(2, 1, "Quisquam est, qui dolorem ipsum")
;

INSERT INTO tags (tag) VALUES
	("sfw"),
	("nsfw"),
	("video"),
	("art"),
	("music")
;

INSERT INTO thread_tags (thread_id, tag_id) VALUES
	(1,1),
	(1,4),
	(2,3),
	(2,4),
	(2,5),
	(3,1),
	(3,2)
;

INSERT INTO thread_likes (thread_id, user_id) VALUES
	(1,1),
	(1,2),
	(1,3),
	(2,1),
	(3,2),
	(3,3)
;

INSERT INTO comment_likes (comment_id, user_id) VALUES
	(1,1),
	(1,2),
	(2,1),
	(2,2),
	(3,1),
	(5,1),
	(6,1),
	(6,2)
;



CREATE VIEW view_tags AS
	SELECT thread_id, title, tag
	FROM tags, threads, thread_tags
	WHERE
		thread_tags.thread_id = threads.id AND
		thread_tags.tag_id = tags.id;

CREATE VIEW thread_with_likes AS
	SELECT thread_id, count(*) AS likes
	FROM threads, thread_likes
	WHERE id = thread_id
	GROUP BY id;
	
CREATE VIEW view_thread_likes AS
	SELECT id AS thread_id, IFNULL(twl.likes, 0) AS likes
	FROM threads
	LEFT JOIN thread_with_likes twl
	ON id = twl.thread_id;

CREATE VIEW comments_with_likes AS
	SELECT comment_id, count(*) AS likes
	FROM comments, comment_likes
	WHERE id = comment_id
	GROUP BY id;

CREATE VIEW view_comment_likes AS
	SELECT id AS comment_id, IFNULL(cwl.likes, 0) AS likes
	FROM comments c
	LEFT JOIN comments_with_likes cwl
	ON id = cwl.comment_id;

	

CREATE VIEW view_comments AS
	SELECT thread_id, c.id AS comment_id, u.id AS user_id, username, avatar, date_created, content, likes
	FROM comments c, users u, view_comment_likes cl
	WHERE
		c.user_id = u.id AND
		cl.comment_id = c.id
	ORDER BY 1, 3;

CREATE VIEW view_threads AS
	SELECT t.id AS thread_id, user_id, username, avatar, date_created, t.title, likes, comments
	FROM users u, threads t, view_thread_likes tl
	WHERE
		u.id = t.user_id AND
		tl.thread_id = t.id;

CREATE VIEW view_thread_tags AS
	SELECT tt.thread_id, title, tt.tag_id, tag
	FROM threads, thread_tags tt, tags
	WHERE
		threads.id = tt.thread_id AND
		tt.tag_id = tags.id;

CREATE VIEW view_threads_with_tags AS
	SELECT t.id AS thread_id, user_id, username, avatar, date_created, t.title, likes, comments, tag
	FROM users u, threads t, view_thread_likes tl, thread_tags tt, tags
	WHERE
		u.id = t.user_id AND
		tl.thread_id = t.id AND
		t.id = tt.thread_id AND
		tt.tag_id = tags.id;