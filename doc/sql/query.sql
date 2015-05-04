SELECT dbr_url.id AS id, COUNT( dbr_urlsegment.id ) AS matches
FROM  `dbr_url` 
JOIN dbr_url_urlsegment ON ( dbr_url.id = dbr_url_urlsegment.dbr_url_id ) 
JOIN dbr_urlsegment ON ( dbr_url_urlsegment.dbr_urlsegment_id = dbr_urlsegment.id ) 
WHERE (
dbr_urlsegment.segment =  'firstlevel'
AND dbr_url_urlsegment.position =1
)
OR (
dbr_urlsegment.segment =  'secondlevel'
AND dbr_url_urlsegment.position =2
)
OR (
dbr_urlsegment.segment =  'thirdlevel'
AND dbr_url_urlsegment.position =3
)
OR (
dbr_urlsegment.segment =  'test'
AND dbr_url_urlsegment.position =4
)
AND dbr_url.segmentcount = 4
GROUP BY dbr_url.id
ORDER BY matches DESC, dbr_url.weight DESC


SELECT dbr_url.id AS id
FROM  `dbr_url` 
JOIN dbr_url_urlsegment AS map1 ON ( dbr_url.id = map1.dbr_url_id AND map1.position = 1) 
JOIN dbr_urlsegment AS seg1 ON ( map1.dbr_urlsegment_id = seg1.id ) 
JOIN dbr_url_urlsegment AS map2 ON ( dbr_url.id = map2.dbr_url_id AND map2.position = 2) 
JOIN dbr_urlsegment AS seg2 ON ( map2.dbr_urlsegment_id = seg2.id )
JOIN dbr_url_urlsegment AS map3 ON ( dbr_url.id = map3.dbr_url_id AND map3.position = 3) 
JOIN dbr_urlsegment AS seg3 ON ( map3.dbr_urlsegment_id = seg3.id )
JOIN dbr_url_urlsegment AS map4 ON ( dbr_url.id = map4.dbr_url_id AND map4.position = 4) 
JOIN dbr_urlsegment AS seg4 ON ( map4.dbr_urlsegment_id = seg4.id )
WHERE
seg1.segment =  'firstlevel'
AND 
seg2.segment =  'secondlevel'
AND 
seg3.segment =  'thirdlevel'
AND 
seg4.segment =  'test'
AND dbr_url.segmentcount = 4
GROUP BY dbr_url.id
ORDER BY dbr_url.weight DESC
