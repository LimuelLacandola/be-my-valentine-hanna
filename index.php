<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>For My Hanna ❤️</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box; font-family:'Poppins',sans-serif;}
body{margin:0; height:100vh; overflow:hidden; background:linear-gradient(135deg,#ff9a9e,#fad0c4); display:flex; justify-content:center; align-items:center; position:relative;}

/* Hearts & Floating Messages */
.heart, .floating-msg{position:absolute; pointer-events:none; font-size:20px;}
.floating-msg{color:white; font-weight:bold; animation:floatUp 3s linear forwards;}

/* Container */
.container{background:white; width:340px; padding:30px; border-radius:20px; text-align:center; box-shadow:0 15px 30px rgba(0,0,0,.2); position:relative; z-index:10;}
input[type="date"]{width:100%; padding:10px; border-radius:10px; border:1px solid #ccc; margin-top:15px;}
button{padding:12px 25px; border:none; border-radius:25px; background:#ff4d6d; color:white; font-size:16px; margin-top:15px; cursor:pointer; transition:.3s;}
button:hover{transform:scale(1.05);}

/* Sections */
.section{display:none;}
.section.active{display:block;}

/* Slideshow */
.slideshow img{width:100%; height:220px; object-fit:cover; border-radius:15px; margin-top:15px;}

/* Moving NO Button */
#noBtn{position:absolute; transition:0.2s;}

/* Confetti */
.confetti{position:absolute; width:10px; height:10px; animation:fall 3s linear infinite;}

@keyframes floatUp{0%{opacity:1; transform:translateY(0);}100%{opacity:0; transform:translateY(-100px);}}
@keyframes fall{to{transform:translateY(100vh) rotate(360deg);}}

/* Love letter modal */
#loveModal{position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); display:none; justify-content:center; align-items:center; z-index:1000;}
#loveContent{background:white; border-radius:20px; padding:30px; text-align:center; max-width:400px; position:relative; animation:fadeIn 0.5s;}
#loveContent h1{margin-bottom:15px;}
#loveContent p{font-size:16px; line-height:1.5;}
#closeLove{position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px; color:#ff4d6d;}

/* YES button animation */
#yesBtn{animation:popIn 0.5s;}
@keyframes popIn{0%{transform:scale(0);}100%{transform:scale(1);}}
</style>
</head>
<body>

<audio id="bgMusic" loop preload="auto">
  <source src="music.mp3" type="audio/mpeg">
</audio>

<div class="container">

<!-- CONFIRM -->
<div id="confirm" class="section active">
  <h2 id="typeText"></h2>
  <p>To confirm you're hanna,<br>enter our anniversary date</p>
  <input type="date" id="anniv">
  <button onclick="checkDate()">Confirm</button>
  <p id="error" style="color:red;display:none;">Try again 💔</p>
</div>

<!-- SLIDESHOW -->
<div id="slideshow" class="section">
  <h2>Our Memories 💕</h2>
  <div class="slideshow">
    <img id="slideImg" src="img1.jpg">
  </div>
  <button onclick="nextToGame()">Next ➡</button>
</div>

<!-- GAME -->
<div id="game" class="section">
  <h2>Catch the hearts 💖</h2>
  <p>Catch at least 5 hearts to unlock the question!</p>
  <p>Caught: <span id="caught">0</span>/5</p>
</div>

<!-- QUESTION -->
<div id="question" class="section">
  <h2 id="nameText"></h2>
  <h2>Will you be my Valentine? 💌</h2>
  <button onclick="yesClicked()" id="yesBtn" style="display:none;">YES 💖</button>
  <button id="noBtn">NO 💔</button>
  <p id="noMsg" style="display:none;color:#ff4d6d;margin-top:10px;">You can't say no 😜</p>
</div>

<!-- RESULT -->
<div id="result" class="section">
  <h1>YEHEEYYY!!! 🎉</h1>
  <h2>I Love You ❤️</h2>
</div>

</div>

<!-- Love Letter Modal -->
<div id="loveModal">
  <div id="loveContent">
    <span id="closeLove">&times;</span>
    <h1>💖 LOVE 💖</h1>
    <p>Every moment with you is special. You make my world brighter. I hope we can make more memories together. Be my Valentine today and always! 💖</p>
  </div>
</div>

<script>
/* ===== CUSTOMIZE ===== */
const herName="LOVE";
const correctDate="2019-11-09";
/* ==================== */

/* Typewriter */
const text=`Hi ${herName} ❤️`;
let i=0;
function typeEffect(){
  if(i<text.length){
    document.getElementById("typeText").innerHTML+=text.charAt(i);
    i++;
    setTimeout(typeEffect,100);
  }
}
typeEffect();

/* Check Date */
function checkDate(){
  const v=document.getElementById("anniv").value;
  if(v===correctDate){
    const music=document.getElementById("bgMusic");
    music.volume=0;
    music.play();
    let vol=0;
    const fade=setInterval(()=>{
      if(vol<1){vol+=0.05; music.volume=vol;} else {clearInterval(fade);}
    },200);
    show("slideshow");
  }else{
    document.getElementById("error").style.display="block";
  }
}

function show(id){
  document.querySelectorAll(".section").forEach(s=>s.classList.remove("active"));
  document.getElementById(id).classList.add("active");
}

/* Slideshow */
const images=[];
for(let i=1;i<=20;i++) images.push(`img${i}.jpg`);
let index=0;
setInterval(()=>{
  if(document.getElementById("slideshow").classList.contains("active")){
    index=(index+1)%images.length;
    document.getElementById("slideImg").src=images[index];
  }
},2000);

function nextToGame(){
  show("game");
  startGame();
}

/* Mini Catch the Heart Game */
let heartsCaught=0;
let gameInterval;
function startGame(){
  heartsCaught=0;
  document.getElementById("caught").innerText=heartsCaught;
  // remove leftover hearts/messages
  document.querySelectorAll(".heart, .floating-msg").forEach(el=>el.remove());

  gameInterval=setInterval(()=>{ spawnHeart(); }, 800);
  // start random floating messages
  messageInterval = setInterval(()=>{ spawnRandomMessage(); }, 1500);
}

function spawnHeart(){
  const h=document.createElement("div");
  h.className="heart";
  h.innerText="❤️";
  h.style.left = Math.random() * (window.innerWidth-50) + "px"; // full width
  h.style.top = "-30px";
  h.style.fontSize = (30 + Math.random()*15) + "px";
  h.style.position = "absolute";
  h.style.cursor = "pointer";
  h.style.pointerEvents = "auto";
  document.body.appendChild(h);

  let top = -30;
  const fall = setInterval(()=>{
    top+=2;
    h.style.top = top + "px";
    if(top>window.innerHeight){ h.remove(); clearInterval(fall); }
  },10);

  h.addEventListener("click", ()=>{
    heartsCaught++;
    document.getElementById("caught").innerText = heartsCaught;
    showFloatingMsg(h.offsetLeft, h.offsetTop, "You got it! 💖");
    h.remove();
    clearInterval(fall);
    if(heartsCaught >=5){
      clearInterval(gameInterval);
      clearInterval(messageInterval);
      const yesBtn = document.getElementById("yesBtn");
      yesBtn.style.display = "inline-block";
      setTimeout(()=>{
        show("question");
        document.getElementById("nameText").innerText = herName + ",";
        confetti();
      }, 400);
    }
  });
}

/* Floating message on catch */
function showFloatingMsg(x,y,text){
  const msg=document.createElement("div");
  msg.className="floating-msg";
  msg.style.left=x+"px";
  msg.style.top=y+"px";
  msg.innerText=text;
  document.body.appendChild(msg);
  setTimeout(()=>msg.remove(),2000);
}

/* Random floating messages during game */
let messageInterval;
const sweetMsgs=["💖 Love you! 💖","😍 You're amazing!","😘 Be mine!","💞 Forever us","🌹 My heart is yours"];
function spawnRandomMessage(){
  const msg=document.createElement("div");
  msg.className="floating-msg";
  msg.style.left=Math.random()*window.innerWidth+"px";
  msg.style.top=window.innerHeight+"px";
  msg.style.color=["#ff4d6d","#ffd166","#06d6a0"][Math.floor(Math.random()*3)];
  msg.style.fontSize = (15+Math.random()*10)+"px";
  msg.innerText = sweetMsgs[Math.floor(Math.random()*sweetMsgs.length)];
  document.body.appendChild(msg);
  const anim = setInterval(()=>{
    let currentTop = parseFloat(msg.style.top);
    msg.style.top = (currentTop - 1) + "px";
    if(currentTop< -50){ msg.remove(); clearInterval(anim); }
  },15);
}

/* Cursor-following hearts */
document.addEventListener("mousemove",e=>{
  const f=document.createElement("div");
  f.className="heart";
  f.style.left=e.clientX+"px";
  f.style.top=e.clientY+"px";
  f.style.fontSize="15px";
  f.style.opacity="0.7";
  document.body.appendChild(f);
  setTimeout(()=>f.remove(),800);
});

/* Moving NO button */
const noBtn=document.getElementById("noBtn");
const noMsg=document.getElementById("noMsg");
function moveNo(){
  const x=Math.random()*220;
  const y=Math.random()*180;
  noBtn.style.left=x+"px";
  noBtn.style.top=y+"px";
}
noBtn.addEventListener("mouseover",moveNo);
noBtn.addEventListener("touchstart",moveNo);
noBtn.addEventListener("click",()=>{
  noMsg.style.display="block";
  setTimeout(()=>noMsg.style.display="none",1500);
});

/* YES clicked → Love letter */
function yesClicked(){
  show("result");
  confetti();
  document.getElementById("loveModal").style.display="flex";
}

/* Close Love Letter */
document.getElementById("closeLove").addEventListener("click",()=>{
  document.getElementById("loveModal").style.display="none";
});

/* Confetti */
function confetti(){
  for(let i=0;i<120;i++){
    const c=document.createElement("div");
    c.className="confetti";
    c.style.left=Math.random()*100+"%";
    c.style.background=["#ff4d6d","#ffd166","#06d6a0"][Math.floor(Math.random()*3)];
    document.body.appendChild(c);
    setTimeout(()=>c.remove(),3000);
  }
}
</script>
</body>
</html>
