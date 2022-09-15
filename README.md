## Jolli API
 Jolli is a utility service for creating Jollirable moments for individual and corporate users worldwide.
 
 Jolli offers:
 
 - Me Hour
 - Tasks/Reminders
 - Notes
 - Smart Goal Set/Tracking
 - Next Generation Audio Chat
 - Jolliverse
 - Gamified/Point System

 ## Including relations
 To include relations when creating/updating models, you can simply append the array in the payload. e.g.

     POST /api/v1/users
     {
         ""first_name"": ""Peter"",
         ""last_name"": ""Obi"",
         ""email"": ""peter@gmail.com"",
         ""address"" [
             ""country_id"": 38,
             ""state_id"": 2000,
             ""city_id"": 16240,
             ""street"": ""14 Becker Street, Myanar"",
             ""post_code"": ""30800""
         ]
     }
 ## Including Resources 
 For all api resources, the index endpoint contains a documentation of the available include for each resource.
 
 The format for requesting data to be included with data input is as follows:

    /api/v1/endpoint?include=csv

 Where csv is basically a comma separated list of relations to include. e.g.:

    /api/v1/users/me?include=role,companies

 Nested includes are also supported, say for example we want to retrieve the user who created a company as well:

    /api/v1/users/me?include=role,companies.user

 ## Contacts
 Opata Chibueze (Lead Developer) - opatachibueze@gmail.com"