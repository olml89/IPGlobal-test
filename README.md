<p align="center"><a href="https://www.ipglobal.es" target="_blank"><img src="https://www.ipglobal.es/wp-content/uploads/2017/12/logo-ipg-techhub.png" width="400" alt="Ipglobal : Tech Hub"></a></p>

<p align="center">
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
<a href="https://codecov.io/gh/olml89/IPGlobal-test"><img src="https://codecov.io/gh/olml89/IPGlobal-test/branch/master/graph/badge.svg?token=SL6ANXRH0A" alt="Coverage status"></a>
</p>

# Requirements

Implementation using **[Laravel 10](https://github.com/laravel/framework)** of a Blog application prototype with two functionalities:

- Page listing the existing posts
- Individual post page, showing the post information and a brief sheet about the author

The blog has to expose a public API with two endpoints:

- **GET /posts/{:postId}** to get the information of a post, including the author's data
- **POST /posts** to publish new posts

You can consult the full specifications in spanish in the [original document](https://github.com/olml89/IPGlobal-test/blob/master/docs/specifications.pdf).
