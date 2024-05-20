## About Project

This is a blog post which people can register to be a contributor. The project consist of 2 parts, backend & frontend which have following spesification:

Backend
- Docker
- PHP: 8.2
- Laravel: 11.0

Frontend
- Inertia JS: 1.0.14
- Vite: 5.0
- Vue: 3.3.13
- Tailwind: 3.4
- Froala Editor: 4.2

Database: 
- MySQL: 8.0


## Overview

People can visit this platform, on a landing page they can see a list of posts that posted by other people and also has a link for them to register/login. After register, they will be automatically logged in and redirected to homepage which now showing a button to create new article. 

If somehow they forget their password, there is a forgot password mechanism. On a login page, there is a link "Forgot your password?", after click that link and provide an email registration. The platform will send an email to reset password (On a local development stage, all emailing process captured by Mailpit that can be accessed on localhost port 8025).

To create an article (/article/form), just need to put title and content. The content has rich text editor that accept html tag, but for more consistent look the system will wiped out any inline styling during saving the article. The default status for new article is draft. During saving the article, the system is also generate summary to show a little bit overview about the article.
The article won't show to the other people if it still draft, only shown by creator itself. The owned article has an 'Edit' button, which then can also change the status to published. After article published, they now have a published date. The article can also be deleted (soft delete by archived it). 

On a detail article page, for logged in people, they can see a like button (heart shapped icon), like counter, submit comment section, comment counter and comment list.

On an article list page, there is a search box that on realtime can filter article by keyword, but the search start after 3rd character being inputted. Emptying search box will reset to show all article.

By default on list article page, also already implemented the pagination which 5 article per page.


## Schema Design

Table **Users**:
- id bigint
- name varchar(255)
- email varchar(255)
- email_verified_at timestamp
- password varchar(255)


Table **Articles**:
- id bigint
- title varchar(255)
- content text
- summary varchar(255)
- user_id bigint fk
- status enum('archived', 'draft', 'published')
- publised_date timestamp

Table **Comments**:
- id bigint
- article_id bigint
- user_id bigint
- content text

Table **Likes**:
- id bigint
- article_id bigint
- user_id bigint
- is_like tinyint(1)

Relationship:
- 1 Article belongs to 1 User
- 1 Article has many comment
- 1 Article has many like/dislike
- 1 Comment belongs to 1 article
- 1 Comment belongs to 1 user
