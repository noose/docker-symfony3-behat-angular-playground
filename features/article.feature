Feature: Manage articles

  As User I need to be able to find articles

  Background:
    Given there are articles with the following details:
    | id | author | date_add            | date_create         | title                                   | description                                                                                                               | link                                            | thumbnail |
    | 1 | bbc    | 2016-05-18 20:33:00 | 2015-12-21 01:02:03 | Chinese businessman agrees Villa deal   | Chinese businessman Dr Tony Xia agrees a deal to buy Aston Villa for £60m following their relegation to the Championship. | http://www.bbc.co.uk/sport/0/football/36327300  | http://c.files.bbci.co.uk/2173/production/_89736580_hi031961660.jpg |
    | 2 | bbc uk | 2016-05-22 18:21:00 | 2016-05-22 18:21:00 | Blur star hosts celeb car-boot sale     | Blur drummer Dave Rowntree rounded up some of his celebrity friends for a charity car-boot sale which was a "big success".| http://www.bbc.co.uk/news/uk-england-london-36343675#sa-ns_mchannel=rss&amp;ns_source=PublicRSS20-sa | |
    | 3 | bbc world | 2016-05-21 23:00:00 | 2016-05-20 18:30:00 | Coleman deal a Euro 'boost' for Wales| Manager Chris Coleman's contract extension will boost Wales' players for Euro 2016, says former international Owain Tudur Jones.| http://www.bbc.co.uk/sport/0/football/36351979                                                 | |

    And when consuming the endpoint I use the "content-type" of "application/json"

  Scenario: User can't find article with given title
    When I send a "GET" request to "/search/?query=non+existing+title"
    Then the response code should 200

  Scenario: User can find articles with given part of the title
    When I send a "GET" request to "/search/?query=man"
    Then the response code should 200
     And the response header "Content-Type" should be equal to "application/json"
     And the response should contain json:
      """
      {"id":1,"title":"Chinese businessman agrees Villa deal","description":"Chinese businessman Dr Tony Xia agrees a deal to buy Aston Villa for \u00a360m following their relegation to the Championship.","dateAdd":"2016-05-18 20:33:00","dateCreate":"2015-12-21 01:02:03","author":"bbc","link":"http:\/\/www.bbc.co.uk\/sport\/0\/football\/36327300","thumbnail":"http:\/\/c.files.bbci.co.uk\/2173\/production\/_89736580_hi031961660.jpg"}
      {"id":3,"title":"Coleman deal a Euro \u0027boost\u0027 for Wales","description":"Manager Chris Coleman\u0027s contract extension will boost Wales\u0027 players for Euro 2016, says former international Owain Tudur Jones.","dateAdd":"2016-05-21 23:00:00","dateCreate":"2016-05-20 18:30:00","author":"bbc world","link":"http:\/\/www.bbc.co.uk\/sport\/0\/football\/36351979","thumbnail":""}
      """
