CREATE TABLE `posts` (
  `id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `published` char(1) NOT NULL
);