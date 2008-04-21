Hello! Thanks for downloading the sample code for the MovieList application, as discussed in Apress's Practical REST on Rails 2 Projects.

In this archive file, you'll find code for each of the projects in the book. These include:
- Chapter 3, developing the main MovieList application
- Chapter 4, creating JavaScript widgets and JSON clients
- Chapter 5, building a Squidoo module in PHP
- Chapter 6, adding an iPhone interface
- Chapter 7, porting MovieList to Facebook
- Chapter 8, dealing with the problems that accompany high-traffic applications

Each chapter's code includes (at least) one Rails application that can be run independently. Dependencies are specified in the chapters themselves (though to run the tests, you'll also need the Mocha gem). When switching from one project to another, however, you should be sure to run rake db:migrate:reset (to ensure that you have the correct database schema).

There are some differences between the code here and that in the book - these are due to a number of issues, but any inaccuracies in the code in the book are entirely my mistake.

Note also that this project is not intended to be a tutorial in testing. For the most part, the tests are of pretty good quality. In the Facebook chapter, however, you'd probably do well to use tests like these as merely a starting point - there are much better ways to test RFacebook and ActiveResource applications than are illustrated here.

One final thing: I'll be periodically updating this code in a git repository at https://github.com/bscofield/movielist/tree/master. Feel free to watch it there. (Or, even better, fork it and improve it!)

Thanks again, and I hope you find this useful!
Ben Scofield
