*URL HASHING*
----------------------------------------

**converting long urls into short hashes**


The overall idea is to take the url, store it into db along with short hashed url, index the short url column for fast searching of the actual url from the hashed URL.

*****APIs*****
------------------------------------------
1. GET request (as indexed, retrieving will also be super fast )
2. POST request (also writing will be fast because of just two columns)

//we can add other tracking features by adding column such as timestamps, request_no's etc.


**Decision on choosing the type of DBMS**
---------------------------------------------------

1. RDBMS
	a. difficult to scale (sharding can be done here though it will increase the complexity when horizontally scaling)

2. NoSQL
	easy to scale (by just addition of node in need of expansion)
	though issue of collision can be present which can be avoided easily in rdbms



*System design choice Explanation*
----------------------------------------------

There were multiple factors that was taken care while creating this simple system for hashing the url and shorting the big into tiny one.

For example: scaling, collisions.

**Solution to collision:**
so, if you would have just created a random string and associate it with the original url then the changes of collision would have created and considering in millions of fields in large scale application this issue would have corrupted the database.
Thus, I have hashed the original url using md5 algorithm and then taken few starting bits and encoded in base64 to further avoid collisions.

**Solution to Scaling:**
As short url field is indexed as it is set to be primary key, thus the searching or lookup is going to be super fast.
But the insertion would have been better in noSQL type DB.
Though assuming searches would be more than the  insertion thus I choosed to go with RDBMS.


*SETUP*
---------------------------------------------
to run this application, you must have php, mysql server installed in you system. You can do the same by just installing xampp or other LAMP web server 

There are basically to API's

1. To Store big url and get minified version
		METHOD: POST
		FIELDS: URL 
		BODY: raw
![image](./screenshots/post.png?raw=true)

2. To fetch the original url using the minified url
		METHOD: GET
		FIELDS: URL 
		BODY: raw
![image](./screenshots/get.png?raw=true)


*DATABASE*

sql file is attached in . location
![image](./screenshots/db.png?raw=true)