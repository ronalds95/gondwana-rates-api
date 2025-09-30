1. Put `api.php` and `index.html` in the same folder on a PHP-capable server.
2. Run locally for testing: `php -S localhost:8000` and open `http://localhost:8000/index.html`.
3. Adjust `unitMap` in `api.php` to match your real unit names to the correct Unit Type IDs.
4. The proxy will forward the converted payload to the remote URL and return the remote response with metadata.

This code also runs here a Backup Server off from Github: https://godwanaapi.adoremedia.club/


Gondwana Collection Rates App - Super Simple Explanation!
What This App Does (The Playground Version)

Imagine you have a magic toy box that can tell you:

    "How much does it cost to stay at a fun lodge?" 

    "Is there room for you and your family?" 

    "When can you go play there?" 

That's exactly what this app does! It's like asking a super-smart robot about vacation places.
The Two Magic Parts
Part 1: The "Brain" (api.php) 

This is like the app's thinking brain!

What it does:

    Listens when you fill out the form

    Talks to Gondwana's computer to ask about prices

    Translates your answers into computer language

    Gives you back the answer in a way you can understand

php

// It's like saying: "Hey computer, can my family stay here?"
$answer = ask_computer_about_rooms($your_family_info);

Part 2: The "Pretty Face" (index.php) 

This is what you see and click on!

What it does:

    Shows you pretty pictures and colors

    Lets you type when you want to go

    Asks how many people are in your family

    Shows the answers in a fun way

html

<!-- It's like a colorful playground you can click on! -->
<button> Check Prices!</button>

🎮 How to Play With It
Step 1: Tell the App About Your Trip
text

"Hey app, I want to stay at:"
☐ Kalahari Anib Lodge    (like a desert castle!)
☐ Namib Desert Lodge     (sand dunes fun!)
☐ Canyon Lodge           (mountain adventure!)
☐ Etosha Safari Camp     (animal watching!)

"When:"
Arrive: 01/10/2025
Leave:  05/10/2025

"Who's coming:"
Dad (34 years old)
Daughter (8 years old)

Step 2: Click the Magic Button!
javascript

// When you click this...
await fetch('./api.php', { magic: true });

// The app talks to the big computer and says:
"Hello Gondwana computer! Can this family stay at Kalahari Lodge?"

Step 3: See the Answer!

The app shows you:
text

SUCCESS! Here's your answer:

Kalahari Anib Lodge
Price: NAD 130,000 (that's like 130,000 candy bars!)
Stay: 4 nights of fun!
Available: YES! You can go! 
Family: 2 people (1 grown-up, 1 kid)

Why It Looks So Pretty

The app wears Gondwana's favorite colors:

    Green 🟢 like the trees in Namibia

    Gold 🟡 like the desert sun

    Brown 🟤 like the earth

It has:

    A friendly header with the company name

    A beautiful picture of Namibia

    Easy boxes to click and type in

    Happy messages when things work

How the Magic Works (Behind the Scenes)
The "Secret Handshake" 

    You fill out the form → "I want to go here with my family!"

    The brain (api.php) translates → "Computer, can they stay from October 1-5?"

    Gondwana's computer answers → "Yes! It costs NAD 130,000"

    The pretty face shows you → "Yay! Here's your vacation price! 

The "Language Translation" 

Your answers get changed into computer talk:
text

You say: "01/10/2025"
Computer understands: "2025-10-01"

You say: "34 years old"
Computer understands: "Adult"

You say: "8 years old"  
Computer understands: "Child"

How to Make It Work
Super Easy Steps:

    Put both files in the same folder (like putting toys in the same box)

    Open terminal (like turning on the toy)

    Type: php -S localhost:8000 (like saying "start playing!")

    Click the link that appears (like opening the toy box)

    Start playing with the form! 🎮

What Makes This App Special

    It's like a friendly robot that helps plan vacations

    It talks to big computers but shows you simple answers

    It remembers Gondwana's favorite colors and style

    It's super safe and only asks questions nicely

    It never gets tired of helping you plan fun trips!

If the App Gets Confused

Sometimes the app might say:
text

"Oops! I can't understand the computer right now!"

This usually means:

    The big Gondwana computer is sleeping 

    The dates are mixed up (like trying to leave before you arrive!)

    Someone forgot to say how old they are

Just check your answers and try again! The app is very patient. 
