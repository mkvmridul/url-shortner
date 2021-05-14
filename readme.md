*URL HASHING*
----------------------------------------

**converting long urls into short hashes**


The overall idea is to take the url, store it into db along with short hashed url, index the short url column for fast searching of the actual url from the hashed URL.

*****APIs*****
------------------------------------------
1. GET request (as indexed, retrieving will also be super fast )
2. POST request (also writing will be fast because of just two columns)

//we can add other tracking features by adding column such as timestamps, request_no's etc.
