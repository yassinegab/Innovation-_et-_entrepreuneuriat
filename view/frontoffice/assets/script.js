





document.addEventListener('DOMContentLoaded', function() {
const tousbtn=document.getElementById('tousbtn');
tousbtn.addEventListener('click',function()
{
  
  window.location.href="index.php"

}

)
  

  // DOM Elements
  const createCollabBtn = document.getElementById('createCollabBtn');
  const modalOverlay = document.getElementById('modalOverlay');
  const cancelBtn = document.getElementById('cancelBtn44');
  const submitBtn = document.getElementById('submitBtn');
  const collabForm = document.getElementById('collabForm');
  const proposalContainer = document.getElementById('proposalContainer');
  const proposalTitle = document.getElementById('proposalTitle');
  const proposalBody = document.getElementById('proposalBody');
  const acceptProposalBtn = document.getElementById('acceptProposalBtn');
  
  // Form Elements
  const projectTitle = document.getElementById('projectTitle');
  const projectDescription = document.getElementById('projectDescription');
  const collaborationType = document.getElementById('prjecttype');
  
  // Show modal
  createCollabBtn.addEventListener('click', function() {
      modalOverlay.classList.add('active');
      projectTitle.focus();
  });
  
  // Hide modal
  cancelBtn.addEventListener('click', function() {
      modalOverlay.classList.remove('active');
  });
  
  // Close modal when clicking outside
  modalOverlay.addEventListener('click', function(event) {
      if (event.target === modalOverlay) {
          modalOverlay.classList.remove('active');
      }
  });
  
  // Generate proposal
  submitBtn.addEventListener('click',async function() {
    const propositions = Array.from(document.querySelectorAll('.proposition-card')).map(card => ({
      type: card.getAttribute('data-type'),
      title: card.querySelector('.card-title')?.innerText,
      description: card.querySelector('.card-description')?.innerText,
      id: card.querySelector('.hiddenid')?.value
  }));



      // Basic form validation
      if (!projectTitle.value || !projectDescription.value  || !collaborationType.value) {
          alert('Please fill in all fields');
          return;
      }
      
  try {
    //console.log(propositions);
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
        { role: 'system', content: JSON.stringify(propositions) + " tu dois choisir parmi ses proposition une qui sera compatible avec le prompt donnees donner la plus proche ou la plus compatible et donner l id seulement sans meme le mot id"},
        { role: 'user', content: "title : "+document.getElementById('projectTitle').value+"description  :"+document.getElementById('projectDescription').value+" type : "+document.getElementById('prjecttype').value }
      ]
    }, {
      headers: {
        'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
        'Content-Type': 'application/json'
      }
    });

    const generatedTitle = response.data.choices?.[0]?.message?.content.trim() || "Titre non généré";
    //console.log(generatedTitle);
    
               window.location.href = "index.php?generatedid="+generatedTitle;
              
          
    
  

    
  } catch (error) {
    console.error("Erreur API :", error);
    alert("Une erreur est survenue lors de la génération du titre.");
  }
   
  
 


      
  });
  
  // Accept proposal
  
  
  // Generate proposal based on form input
  
  
  
  
});

















async function autotitre() {
  const titre = document.getElementById("titre").value.trim();
  let systemPrompt = "";
  let userPrompt = "";

  if (!titre) {
    systemPrompt = "Tu dois générer un titre valable pour une proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement un titre, sans commentaire.";
    userPrompt = "Génère un titre";
  } else {
    systemPrompt = "Tu dois corriger le titre donné pour qu'il soit valable comme proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement un titre corrigé, sans commentaire.";
    userPrompt = titre;
  }

  try {
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt }
      ]
    }, {
      headers: {
        'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
        'Content-Type': 'application/json'
      }
    });

    const generatedTitle = response.data.choices?.[0]?.message?.content.trim() || "Titre non généré";
    document.getElementById("titre").value = generatedTitle;
    
  } catch (error) {
    console.error("Erreur API :", error);
    alert("Une erreur est survenue lors de la génération du titre.");
  }


    

    // Add bot response
    const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
    document.getElementById("titre").value=botMessage; 
   
}



async function autodesc() {
  
  const desc = document.getElementById("description").value.trim();
  const titre = document.getElementById("titre").value.trim();
  let systemPrompt = "";
  let userPrompt = "";

  if (!desc) {
    if(titre){
    systemPrompt = "Tu dois générer une description valable pour une proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement une description, sans commentaire. sur ce titre pour ce titre "+titre;
    }
    else systemPrompt = "Tu dois générer une description valable pour une proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement une description, sans commentaire. ";
    userPrompt = "Génère une description";
  } else {
    if(titre)
    
    systemPrompt = "Tu dois corriger la description donnée pour qu'elle soit valable comme proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement une description corrigée, sans commentaire.pour ce titre"+titre;
    else     systemPrompt = "Tu dois corriger la description donnée pour qu'elle soit valable comme proposition de collaboration dans un site d'entrepreneuriat. Donne uniquement une description corrigée, sans commentaire.";

    userPrompt = desc;
  }

  try {
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt }
      ]
    }, {
      headers: {
        'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
        'Content-Type': 'application/json'
      }
    });

    const botMessage = response.data.choices?.[0]?.message?.content?.trim() || 'No response from API';
    document.getElementById("description").value = botMessage;

  } catch (error) {
    console.error("Erreur API :", error);
    alert("Une erreur est survenue lors de la génération de la description.");
  }
}

async function autorole() {
  const titre = document.getElementById("role").value.trim();
  let systemPrompt = "";
  let userPrompt = "";

  if (!titre) {
    systemPrompt = "Tu dois générer un role valable pour une collaboration dans un site d'entrepreneuriat. Donne uniquement un role, sans commentaire.";
    userPrompt = "Génère un titre";
  } else {
    systemPrompt = "Tu dois corriger le role donné pour qu'il soit valable pour collaboration dans un site d'entrepreneuriat. Donne uniquement un titre corrigé, sans commentaire.";
    userPrompt = titre;
  }

  try {
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt }
      ]
    }, {
      headers: {
        'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
        'Content-Type': 'application/json'
      }
    });

    const generatedTitle = response.data.choices?.[0]?.message?.content.trim() || "Titre non généré";
    document.getElementById("role").value = generatedTitle;
    
  } catch (error) {
    console.error("Erreur API :", error);
    alert("Une erreur est survenue lors de la génération du titre.");
  }


    

    // Add bot response
   
}


async function autotypee() {
  const titre = document.getElementById("typecollab").value.trim();
  let systemPrompt = "";
  let userPrompt = "";

  if (!titre) {
    systemPrompt = "Tu dois générer un type valable pour une collaboration dans un site d'entrepreneuriat. Donne uniquement un role, sans commentaire.";
    userPrompt = "Génère un titre";
  } else {
    systemPrompt = "Tu dois corriger le type donné pour qu'il soit valable pour collaboration dans un site d'entrepreneuriat. Donne uniquement un titre corrigé, sans commentaire.";
    userPrompt = titre;
  }

  try {
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
        { role: 'system', content: systemPrompt },
        { role: 'user', content: userPrompt }
      ]
    }, {
      headers: {
        'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
        'Content-Type': 'application/json'
      }
    });

    const generatedTitle = response.data.choices?.[0]?.message?.content.trim() || "Titre non généré";
    document.getElementById("typecollab").value = generatedTitle;
    
  } catch (error) {
    console.error("Erreur API :", error);
    alert("Une erreur est survenue lors de la génération du titre.");
  }


    

  
   
}





document.addEventListener("DOMContentLoaded", function () {
  const button = document.getElementById("autodesc");
  button.addEventListener("click", autodesc);
});


document.getElementById('propositionForm').addEventListener('submit', async function(event) {
  let titre = document.getElementById('titre').value;
  let description = document.getElementById('description').value;
  let test =true;
  event.preventDefault();
  try {
    // TODO: Replace with server-side API call for security
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
        model: 'gpt-4o-mini',
        messages: [
            { role: 'system', content: 'le prompt donnees est un titre d une proposition doonee par l utilisateur dans un site d entrepreureat verifier s il peut etre un titre repondre seulement par vrai sinon repondre en indiquant le probleme du titre donnee' },
            { role: 'user', content: titre } // Use user input
        ]
    }, {
        headers: {
            'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
            'Content-Type': 'application/json'
        }
    });

    

    // Add bot response
    const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
    if (botMessage.trim().toLowerCase() !== 'vrai'){
      const label = document.getElementById("monLabel");
      label.innerText = botMessage; // Tu peux mettre le texte que tu veux ici
      label.style.display = "block";
      test=false;
    }
    
} catch (error) {
    // Remove loading animation
    

    // Show error message
    console.error('Error:', error);
    
}
try {
  // TODO: Replace with server-side API call for security
  const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
          { role: 'system', content: 'le prompt donnees est une desciption d une proposition doonee par l utilisateur dans un site d entrepreureat verifier si elle peut etre une desciption repondre seulement par vrai sinon repondre en indiquant le probleme de la decription' },
          { role: 'user', content: description } // Use user input
      ]
  }, {
      headers: {
          'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
          'Content-Type': 'application/json'
      }
  });

  

  // Add bot response
  const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
  if (botMessage.trim().toLowerCase() !== 'vrai'){
    const label = document.getElementById("monLabel2");
    label.innerText = botMessage; // Tu peux mettre le texte que tu veux ici
    label.style.display = "block";
    test=false;
    
  }
  
} catch (error) {
  // Remove loading animation
  

  // Show error message
  console.error('Error:', error);
 
}
console.log(test);
if(test)this.submit();


  // Vérification du titre
  /*if (titre.trim().length < 3 || titre.trim().length > 100) {
     // alert('Le titre doit comporter entre 3 et 100 caractères.');
      //event.preventDefault();  // Empêche l'envoi du formulaire
      const label = document.getElementById("monLabel");
      label.innerText = "Le titre doit comporter entre 3 et 100 caractères."; // Tu peux mettre le texte que tu veux ici
      label.style.display = "block";
      event.preventDefault();
     
}
     
  

  // Vérification de la description
  if (description.trim().length < 10 || description.trim().length > 500) {
    const label = document.getElementById("monLabel2");
      label.innerText = "la description doit contenir au moin 50 caracteres"; // Tu peux mettre le texte que tu veux ici
      label.style.display = "block";
      event.preventDefault();
      
     
  }*/
});
document.getElementById('propositionForm3').addEventListener('submit', async function(event) {
  let titre = document.getElementById('role').value;
  let description = document.getElementById('typecollab').value;
  let test =true;
  event.preventDefault();
  try {
    // TODO: Replace with server-side API call for security
    const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
        model: 'gpt-4o-mini',
        messages: [
            { role: 'system', content: 'le prompt donnees est un role d une collaboration doonee par l utilisateur dans un site d entrepreureat verifier s il peut etre un role repondre seulement par vrai sinon repondre en indiquant le probleme du role donnee' },
            { role: 'user', content: titre } // Use user input
        ]
    }, {
        headers: {
            'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
            'Content-Type': 'application/json'
        }
    });

    

    // Add bot response
    const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
    if (botMessage.trim().toLowerCase() !== 'vrai'){
      const label = document.getElementById("monLabelcollab");
      label.innerText = botMessage; // Tu peux mettre le texte que tu veux ici
      label.style.display = "block";
      test=false;
    }
    
} catch (error) {
    // Remove loading animation
    

    // Show error message
    console.error('Error:', error);
    
}
try {
  // TODO: Replace with server-side API call for security
  const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
      model: 'gpt-4o-mini',
      messages: [
          { role: 'system', content: 'le prompt donnees est une type d une proposition doonee par l utilisateur dans un site d entrepreureat verifier si il peut etre une type repondre seulement par vrai sinon repondre en indiquant le probleme de la decription' },
          { role: 'user', content: description } // Use user input
      ]
  }, {
      headers: {
          'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
          'Content-Type': 'application/json'
      }
  });

  

  // Add bot response
  const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
  if (botMessage.trim().toLowerCase() !== 'vrai'){
    const label = document.getElementById("monLabel2collab");
    label.innerText = botMessage; // Tu peux mettre le texte que tu veux ici
    label.style.display = "block";
    test=false;
    
  }
  
} catch (error) {
  // Remove loading animation
  

  // Show error message
  console.error('Error:', error);
 
}
console.log(test);
if(test)this.submit();


 
});
document.addEventListener('DOMContentLoaded', function() {
  const openChatbotButton = document.getElementById('openChatbotButton');
  const chatbotContainer = document.getElementById('chatbotContainer');
  const chatBox = document.getElementById('chatBox');
  const inputMessage = document.getElementById('inputMessage');
  const sendMessageButton = document.getElementById('sendMessageButton');

  // Auto-resize textarea
  inputMessage.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
      if (this.scrollHeight > 120) {
          this.style.overflowY = 'auto';
      } else {
          this.style.overflowY = 'hidden';
      }
  });

  // Toggle chatbot visibility
  openChatbotButton.addEventListener('click', function() {
      if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
          chatbotContainer.style.display = 'flex';
          if (chatBox.children.length <= 1) {
              setTimeout(() => {
                  addBotMessage("Hello! How can I assist you today?");
              }, 500);
          }
      } else {
          chatbotContainer.style.display = 'none';
      }
  });

  // Close button in header
  document.querySelector('.header-icon:last-child').addEventListener('click', function() {
      chatbotContainer.style.display = 'none';
  });

  // Send message on button click
  sendMessageButton.addEventListener('click', sendMessage);

  // Send message on Enter key (but allow Shift+Enter for new lines)
  inputMessage.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          sendMessage();
      }
  });

  async function sendMessage() {
      const userMessage = inputMessage.value.trim();
      if (!userMessage) return; // Ignore empty messages

      // Add user message to chat
      addUserMessage(userMessage);
      inputMessage.value = '';
      inputMessage.style.height = 'auto';

      // Show loading animation
      const loadingDiv = document.createElement('div');
      loadingDiv.className = 'loading';
      for (let i = 0; i < 3; i++) {
          const span = document.createElement('span');
          loadingDiv.appendChild(span);
      }
      chatBox.appendChild(loadingDiv);
      chatBox.scrollTop = chatBox.scrollHeight;

      try {

        const propositions = Array.from(document.querySelectorAll('.proposition-card')).map(card => ({
          type: card.getAttribute('data-type'),
          title: card.querySelector('.card-title')?.innerText,
          description: card.querySelector('.card-description')?.innerText,
          id: card.querySelector('.hiddenid')?.value
      }));
          // TODO: Replace with server-side API call for security
          const response = await axios.post('https://api.zukijourney.com/v1/chat/completions', {
              model: 'gpt-4o-mini',
              messages: [
                  { role: 'system', content:JSON.stringify(propositions)+'se sont les propositions d un site d entrepreunareat tu dois repondre sur ces propositions ' },
                  { role: 'user', content: userMessage } // Use user input
              ]
          }, {
              headers: {
                  'Authorization': 'Bearer zu-3ec0bd02b3ecd92935edacb4b4c1a791',
                  'Content-Type': 'application/json'
              }
          });

          // Remove loading animation
          chatBox.removeChild(loadingDiv);

          // Add bot response
          const botMessage = response.data.choices?.[0]?.message?.content || 'No response from API';
          addBotMessage(botMessage);
      } catch (error) {
          // Remove loading animation
          chatBox.removeChild(loadingDiv);

          // Show error message
          console.error('Error:', error);
          addBotMessage('Sorry, I encountered an error. Please try again.');
      }
  }

  function getCurrentTime() {
      const now = new Date();
      let hours = now.getHours();
      let minutes = now.getMinutes();
      const ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12 || 12;
      minutes = minutes < 10 ? '0' + minutes : minutes;
      return hours + ':' + minutes + ' ' + ampm;
  }

  function addUserMessage(text) {
      const container = document.createElement('div');
      container.className = 'message-container user-container';
      const avatar = document.createElement('div');
      avatar.className = 'avatar user-avatar';
      avatar.textContent = 'U';
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message user-message';
      messageDiv.textContent = text;
      const timeDiv = document.createElement('div');
      timeDiv.className = 'message-time';
      timeDiv.textContent = getCurrentTime();
      messageDiv.appendChild(timeDiv);
      container.appendChild(messageDiv);
      container.appendChild(avatar);
      chatBox.appendChild(container);
      chatBox.scrollTop = chatBox.scrollHeight;
  }

  function addBotMessage(text) {
      const container = document.createElement('div');
      container.className = 'message-container bot-container';
      const avatar = document.createElement('div');
      avatar.className = 'avatar bot-avatar';
      avatar.textContent = 'AI';
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message bot-message';
      messageDiv.textContent = text;
      const timeDiv = document.createElement('div');
      timeDiv.className = 'message-time';
      timeDiv.textContent = getCurrentTime();
      messageDiv.appendChild(timeDiv);
      container.appendChild(avatar);
      container.appendChild(messageDiv);
      chatBox.appendChild(container);
      chatBox.scrollTop = chatBox.scrollHeight;
  }
});





document.addEventListener('DOMContentLoaded', function() {
  
  // DOM Elements
  const msgButton = document.querySelectorAll('.msg-button');
  const chatContainer = document.getElementById('chatContainer');
  const closeChat = document.getElementById('closeChat');
  const chatMessages = document.getElementById('chatMessages');
  const messageInput = document.getElementById('messageInput');
  const sendButton = document.getElementById('sendButton');
  const btnmessageie = document.getElementById('btnmessagerie');
  var testscrol=true;

btnmessageie.addEventListener('click', () => {
  const idreceiver=prompt("donner l id du user");

  const idsender = btnmessageie.dataset.idsender;
 
  chatContainer.classList.add('active');
      document.getElementById('chatttile').innerHTML = `
      <input id="id_sender" type="hidden" value="${idsender}">
      <input id="id_receiver" type="hidden" value="${idreceiver}">
      `;

     // renderMessages();
      messageInput.focus();
      loadMessages();


})
  
  // Sample messages
 
  closeChat.addEventListener('click', function() {
    chatContainer.classList.remove('active');
});
  msgButton.forEach(button => {
    button.addEventListener('click', () => {
      
      // Faire défiler jusqu'au formulaire
      const titre = button.dataset.nom;
      const idsender = button.dataset.idsender;
      const idreceiver = button.dataset.idreceiver;
      chatContainer.classList.add('active');
      document.getElementById('chatttile').innerHTML = `<span class="status-indicator"></span> ${titre}
      <input id="id_sender" type="hidden" value="${idsender}">
      <input id="id_receiver" type="hidden" value="${idreceiver}">
      `;

     // renderMessages();
      messageInput.focus();
      loadMessages();
    
    })})
    function sendMessage() {
      const messageText = messageInput.value.trim();
      if (messageText) {
        const message = messageText;
        const senderId = document.getElementById('id_sender').value;       // à adapter selon ta session
        const receiverId = document.getElementById('id_receiver').value;     // à adapter selon l'utilisateur ciblé
      
        fetch("insert_message.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            sender: senderId,
            receiver: receiverId,
            content: message
          })
        })
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            console.log("Message enregistré !");
          } else {
            console.error("Erreur:", data.error);
          }
        });
          
          // Clear input
          messageInput.value = '';
         
        } 
        loadMessages();
        renderMessages();}
        function getCurrentTime() {
          const now = new Date();
          return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      }
      function getCurrentTime2()
      {
        
          const now = new Date();
          return now.toLocaleString('fr-FR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
          });
        
        
      }
      chatMessages.addEventListener('scroll', () => {
        // Tu peux aussi vérifier si l'utilisateur est en bas du scroll
        const isAtBottom = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight;
        if (isAtBottom) {
           testscrol=true;
        }
        else testscrol=false;
    });
       function renderMessages() {
                chatMessages.innerHTML = '';
                
                sampleMessages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.className = `message ${message.type}`;
                    
                    messageElement.innerHTML = `
                        ${message.text}
                        <div class="message-time">${message.time}</div>
                    `;
                    
                    chatMessages.appendChild(messageElement);
                });
                
                if(testscrol)
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            
            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
            
            // Initial render
           // renderMessages();

           var sampleMessages = [];
            function loadMessages() {
              fetch('get_messages.php')
                  .then(response => response.json()) // Convertir la réponse en JSON
                  .then(data => {
                      if (data.success) {
                        sampleMessages=[];
                          const messages = data.messages;  // Tableau de messages
          
          
                          messages.forEach(message => {
                              if(message.id_sender==document.getElementById('id_sender').value && message.id_receiver==document.getElementById('id_receiver').value)
                              {
                                const userMessage = {
                                  text: message.conetnu,
                                  type: "sent",
                                  time: message.date
                              };
                              sampleMessages.push(userMessage);
                              renderMessages();
                              }
                              else if(message.id_receiver==document.getElementById('id_sender').value && message.id_sender==document.getElementById('id_receiver').value )
                              {
                                const responseMessage = {
                                  text: message.conetnu,
                                  type: "received",
                                  time: message.date
                              };
                              sampleMessages.push(responseMessage);
                              renderMessages();
                              }
                          });
                      } else {
                          console.error("Erreur :", data.error);
                      }
                  })
                  .catch(error => console.error("Erreur de récupération des messages :", error));
          }
          
          setInterval(loadMessages, 2000);

        
              



  // Toggle chat interface
// Render messages

  
 


})



function chargerDonnees() {
  fetch("get_donnees.php")
      .then(response => response.text())
      .then(data => {
          document.getElementById("notifContainer").innerHTML = data;
          const notifCount = document.getElementById('notifCount');
      let notificationCount = document.querySelectorAll('.notif-card').length;
      notifCount.textContent = notificationCount;
      const affichermodal = document.querySelectorAll('.btn_affmodal');
const modalOverlay = document.getElementById('modalOverlay2');
const collabData = document.getElementById('collabData');
const cancelBtncollab = document.getElementById('cancelBtncollab');
//const confirmBtn = document.getElementById('confirmBtn');
affichermodal.forEach(button => {
  button.addEventListener('click', () => {

        const collaborationData = {
          userName: button.dataset.nameuser,
          proposalName: button.dataset.titreprop,
          role: button.dataset.role,
          startDate:button.dataset.date1,
          endDate: button.dataset.date2,
          type: button.dataset.type,
          id:button.dataset.id,
          id2:button.dataset.id2
        };
        console.log('proppppp'+collaborationData.proposalName);
        // Format date function
        function formatDate(dateString) {
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          return new Date(dateString).toLocaleDateString('fr-FR', options);
        }

        // Show modal with collaboration data
        
          // Populate the modal with collaboration data
          collabData.innerHTML = `
              <input type="hidden" name="iddemandee" id="iddemandee"  value="${collaborationData.id}">
              <input type="hidden" name="idpropp" id="idpropp"  value="${collaborationData.id2}">
              <div class="data-row">
                  <div class="data-label">Utilisateur:</div>
                  <div class="data-value">${collaborationData.userName}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Proposition:</div>
                  <div class="data-value">${collaborationData.proposalName}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Rôle:</div>
                  <div class="data-value">${collaborationData.role}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Date début:</div>
                  <div class="data-value">${formatDate(collaborationData.startDate)}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Date fin:</div>
                  <div class="data-value">${formatDate(collaborationData.endDate)}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Type:</div>
                  <div class="data-value">${collaborationData.type}</div>
              </div>
          `;
          
          // Show the modal
          modalOverlay.classList.add('active');
        

       
        // Event listeners
        
        });

        // Close modal when clicking outside
       });
       function hideModal() {
        modalOverlay.classList.remove('active');
    }
    
    // Event listeners
    
    
    cancelBtncollab.addEventListener('click', hideModal);
    
   
    
    // Close modal when clicking outside
    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            hideModal();
        }
    });



      });
      
}
chargerDonnees();

// Rafraîchir toutes les 2 secondes
setInterval(chargerDonnees, 3000);





function sendId() {
  let userId = prompt("Veuillez entrer votre ID :");
  if (userId !== "") {
    //window.location.href="index.php?=id="+encodeURIComponent(userId);
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "index.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("id=" + encodeURIComponent(userId));
      
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              console.log("Réponse du serveur : " + xhr.responseText);
              window.location.href = "index.php";
          }
      };
  } else {
      alert("Veuillez entrer un ID.");
  }
}
function destroysession() {
  

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "index.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("destroy=1");
      
      xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
              console.log("Réponse du serveur : " + xhr.responseText);
              window.location.href = "index.php";
              
          }
    
  
}}





const notifBtn = document.getElementById('notifBtn');
        const notifPanel = document.getElementById('notifPanel');
        const notifContainer = document.getElementById('notifContainer');
        const notifCount = document.getElementById('notifCount');
        const overlay = document.getElementById('overlay');
        
        // Compteur de notifications
        /*let notificationCount = document.querySelectorAll('.notif-card').length;
        notifCount.textContent = notificationCount;*/

        // Gérer l'acceptation d'une notification
        function handleApprove(e) {
            const notifId = e.target.dataset.id;
            const notifCard = document.querySelector(`.notif-card[data-id="${notifId}"]`);
            
            if (notifCard) {
                // Ici vous pouvez ajouter la logique pour traiter l'acceptation
                // Par exemple, envoyer une requête à un serveur
                
                const author = notifCard.querySelector('.notif-author').textContent;
                notifCard.remove();
                
                // Mettre à jour le compteur
                notificationCount--;
                //notifCount.textContent = notificationCount;
                
                if (notificationCount === 0) {
                    notifCount.style.display = 'none';
                    notifContainer.innerHTML = '<div class="empty-notif">Aucune notification</div>';
                }
                
                // Afficher une notification
                alert(`Message de ${author} accepté!`);
            }
        }

        // Gérer le refus d'une notification
        function handleDecline(e) {
            const notifId = e.target.dataset.id;
            const notifCard = document.querySelector(`.notif-card[data-id="${notifId}"]`);
            
            if (notifCard) {
                // Ici vous pouvez ajouter la logique pour traiter le refus
                
                const author = notifCard.querySelector('.notif-author').textContent;
                notifCard.remove();
                
                // Mettre à jour le compteur
                notificationCount--;
                notifCount.textContent = notificationCount;
                
                if (notificationCount === 0) {
                    notifCount.style.display = 'none';
                    notifContainer.innerHTML = '<div class="empty-notif">Aucune notification</div>';
                }
                
                // Afficher une notification
                alert(`Message de ${author} refusé!`);
            }
        }

        // Ouvrir/fermer le panel
        function togglePanel() {
            notifPanel.classList.toggle('visible');
            overlay.classList.toggle('visible');
        }

        // Fermer le panel quand on clique ailleurs
        function closePanel() {
            notifPanel.classList.remove('visible');
            overlay.classList.remove('visible');
        }

        // Écouteurs d'événements
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            togglePanel();
        });

        overlay.addEventListener('click', closePanel);

        // Ajouter les écouteurs d'événements pour les boutons
        
       




const profileIcon = document.getElementById('profileIcon');
const profileMenu = document.getElementById('profileMenu');
const initialsElement = profileIcon.querySelector('.initials');

// Fonction pour charger l'image de profil
function loadProfileImage(imageUrl, userName) {
    // Créer l'élément image
    const img = new Image();
    
    // Définir l'URL de l'image
    img.src = imageUrl;
    
    // Gérer le chargement réussi de l'image
    img.onload = function() {
        // Supprimer les initiales
        if (initialsElement) {
            initialsElement.remove();
        }
        
        // Ajouter l'image au conteneur
        profileIcon.appendChild(img);
    };
    
    // Gérer l'erreur de chargement de l'image
    img.onerror = function() {
        console.log("Erreur de chargement de l'image, utilisation des initiales à la place");
        // S'assurer que les initiales sont correctes
        setUserInitials(userName);
    };
}

// Fonction pour définir les initiales de l'utilisateur
function setUserInitials(name) {
    const nameParts = name.split(' ');
    let initials = '';
    
    if (nameParts.length >= 2) {
        initials = nameParts[0].charAt(0) + nameParts[1].charAt(0);
    } else {
        initials = nameParts[0].charAt(0);
    }
    
    initialsElement.textContent = initials.toUpperCase();
}

// Ajouter un événement de clic à l'icône de profil
profileIcon.addEventListener('click', function() {
    profileMenu.classList.toggle('active');
});

// Fermer le menu si on clique ailleurs sur la page
document.addEventListener('click', function(event) {
    if (!profileIcon.contains(event.target) && !profileMenu.contains(event.target)) {
        profileMenu.classList.remove('active');
    }
});

// Exemple d'utilisation - remplacez par les informations de l'utilisateur connecté
const userName = "moez touil";
const userEmail = "moez.touil@esprit.tn";
const profileImageUrl = "assets/398582438_2615487838627907_5927319269485945046_n.jpg"; // URL d'exemple

// Mettre à jour les informations dans le menu
document.querySelector('.profile-header .name').textContent = userName;
document.querySelector('.profile-header .email').textContent = userEmail;

// Charger l'image de profil
loadProfileImage(profileImageUrl, userName);




   


document.addEventListener('DOMContentLoaded', () => {
  const showFormBtn = document.getElementById('showFormBtn');
  const formContainer = document.getElementById('formContainer');
  const cancelBtn = document.getElementById('cancelBtn');
  const propositionForm = document.getElementById('propositionForm');
  

  const editButtons = document.querySelectorAll('.btn-edit');
  const formContainer2 = document.getElementById('formContainer2');
  //const propositionForm2 = document.getElementById('propositionForm');
  const cancelBtn2 = document.getElementById('cancelBtn2');

  const ahoutercollab = document.querySelectorAll('.btn_addcollab');
  const formContainer3 = document.getElementById('formContainer3');
  const cancelBtn3 = document.getElementById('cancelBtn3');

  
  // Définir la date du jour comme valeur par défaut pour le champ date
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('date_soumission').value = today;

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      formContainer2.classList.add('expanding');
      formContainer2.classList.add('active');
      
      // Faire défiler jusqu'au formulaire
      const titre = button.dataset.titre;
      const description = button.dataset.description;
      const type = button.dataset.type;
      const date = button.dataset.date;

      // Remplissage des champs
      document.getElementById('titre2').value = titre;
      document.getElementById('description2').value = description;
      document.getElementById('type2').value = type;
      document.getElementById('date_soumission2').value = date;
      document.getElementById('idmodif').value = button.dataset.id;;

        setTimeout(() => {
          formContainer2.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);

    });
});


cancelBtn2.addEventListener('click', () => {
  formContainer2.classList.remove('expanding');
  formContainer2.classList.add('collapsing');
  
  setTimeout(() => {
      formContainer2.classList.remove('active');
      formContainer2.classList.remove('collapsing');
      
      // Faire défiler jusqu'au bouton d'ajout
      showFormBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }, 500);
});
  


  
  // Afficher le formulaire avec animation
  showFormBtn.addEventListener('click', () => {
      formContainer.classList.add('expanding');
      formContainer.classList.add('active');
      
      // Faire défiler jusqu'au formulaire
      setTimeout(() => {
          formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);
  });
  
  // Masquer le formulaire
  cancelBtn.addEventListener('click', () => {
      formContainer.classList.remove('expanding');
      formContainer.classList.add('collapsing');
      
      setTimeout(() => {
          formContainer.classList.remove('active');
          formContainer.classList.remove('collapsing');
          
          // Faire défiler jusqu'au bouton d'ajout
          showFormBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 500);
  });


  ahoutercollab.forEach(button => {
    button.addEventListener('click', () => {
      formContainer3.classList.add('expanding');
      formContainer3.classList.add('active');
      
      // Faire défiler jusqu'au formulaire
      const id = button.dataset.id;
      

      // Remplissage des champs
      document.getElementById('idproposs').value = id;
      
        setTimeout(() => {
          formContainer3.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 100);

    });
});

// Masquer le formulaire
cancelBtn3.addEventListener('click', () => {
    formContainer3.classList.remove('expanding');
    formContainer3.classList.add('collapsing');
    
    setTimeout(() => {
        formContainer3.classList.remove('active');
        formContainer3.classList.remove('collapsing');
        
        // Faire défiler jusqu'au bouton d'ajout
        showFormBtn3.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 500);
});

/*const affichermodal = document.querySelectorAll('.btn_affmodal');
const modalOverlay = document.getElementById('modalOverlay');
const collabData = document.getElementById('collabData');
const cancelBtncollab = document.getElementById('cancelBtncollab');
//const confirmBtn = document.getElementById('confirmBtn');
affichermodal.forEach(button => {
  button.addEventListener('click', () => {

        const collaborationData = {
          userName: button.dataset.nameuser,
          proposalName: button.dataset.titreprop,
          role: button.dataset.role,
          startDate:button.dataset.date1,
          endDate: button.dataset.date2,
          type: button.dataset.type,
          id:button.dataset.id,
          id2:button.dataset.id2
        };

        // Format date function
        function formatDate(dateString) {
          const options = { year: 'numeric', month: 'long', day: 'numeric' };
          return new Date(dateString).toLocaleDateString('fr-FR', options);
        }

        // Show modal with collaboration data
        
          // Populate the modal with collaboration data
          collabData.innerHTML = `
              <input type="hidde" name="iddemandee" id="iddemandee"  value="${collaborationData.id}">
              <input type="hidde" name="idpropp" id="idpropp"  value="${collaborationData.id2}">
              <div class="data-row">
                  <div class="data-label">Utilisateur:</div>
                  <div class="data-value">${collaborationData.userName}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Proposition:</div>
                  <div class="data-value">${collaborationData.proposalName}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Rôle:</div>
                  <div class="data-value">${collaborationData.role}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Date début:</div>
                  <div class="data-value">${formatDate(collaborationData.startDate)}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Date fin:</div>
                  <div class="data-value">${formatDate(collaborationData.endDate)}</div>
              </div>
              <div class="data-row">
                  <div class="data-label">Type:</div>
                  <div class="data-value">${collaborationData.type}</div>
              </div>
          `;
          
          // Show the modal
          modalOverlay.classList.add('active');
        

       
        // Event listeners
        
        });

        // Close modal when clicking outside
       });
       function hideModal() {
        modalOverlay.classList.remove('active');
    }
    
    // Event listeners
    
    
    cancelBtncollab.addEventListener('click', hideModal);
    
   
    
    // Close modal when clicking outside
    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            hideModal();
        }
    });*/
  
});



document.addEventListener('DOMContentLoaded', () => {
  // Gestion des filtres
  
  const filterButtons = document.querySelectorAll('.filter-btn');
  const propositionCards = document.querySelectorAll('.proposition-card');
  
  filterButtons.forEach(btn => {
    
      btn.addEventListener('click', () => {
          // Retirer la classe active de tous les boutons
          filterButtons.forEach(b => b.classList.remove('active'));
          // Ajouter la classe active au bouton cliqué
          btn.classList.add('active');

          // Filtrer les propositions
          const filterValue = btn.getAttribute('data-filter').toLowerCase();
          
          propositionCards.forEach(card => {
              if (filterValue === 'tous') {
               
                  card.classList.remove('hidden');
              } else {
                  const cardType = card.getAttribute('data-type').toLowerCase();
                  if (cardType === filterValue) {
                      card.classList.remove('hidden');
                  } else {
                      card.classList.add('hidden');
                  }
              }
          });
      });
  });

  // Gestion de la recherche
  const searchInput = document.querySelector('.search-input');
  const searchBtn = document.querySelector('.search-btn');

  function performSearch() {
      const searchText = searchInput.value.toLowerCase();
      
      if (searchText) {
          propositionCards.forEach(card => {
              const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();
              const cardDescription = card.querySelector('.card-description').textContent.toLowerCase();
              const cardUser = card.querySelector('.card-user span').textContent.toLowerCase();
              
              if (cardTitle.includes(searchText) || 
                  cardDescription.includes(searchText) || 
                  cardUser.includes(searchText)) {
                  card.classList.remove('hidden');
              } else {
                  card.classList.add('hidden');
              }
          });
          
          // Réinitialiser les filtres
          filterButtons.forEach(b => b.classList.remove('active'));
          filterButtons[0].classList.add('active'); // Activer "Tous"
      } else {
          // Si la recherche est vide, afficher toutes les propositions
          propositionCards.forEach(card => {
              card.classList.remove('hidden');
          });
      }
  }

  searchBtn.addEventListener('click', performSearch);

  // Permettre la recherche en appuyant sur Entrée
  searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
          performSearch();
      }
  });
});



document.addEventListener('DOMContentLoaded', () => {
            // Gestion des filtres
            const filterButtons = document.querySelectorAll('.filter-btn');
            const propositionCards = document.querySelectorAll('.proposition-card');
            
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Retirer la classe active de tous les boutons
                    filterButtons.forEach(b => b.classList.remove('active'));
                    // Ajouter la classe active au bouton cliqué
                    btn.classList.add('active');

                    // Filtrer les propositions
                    const filterValue = btn.getAttribute('data-filter').toLowerCase();
                    
                    propositionCards.forEach(card => {
                        if (filterValue === 'tous') {
                            card.classList.remove('hidden');
                        } else {
                            const cardType = card.getAttribute('data-type').toLowerCase();
                            if (cardType === filterValue) {
                                card.classList.remove('hidden');
                            } else {
                                card.classList.add('hidden');
                            }
                        }
                    });
                });
            });

            // Gestion de la recherche
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.querySelector('.search-btn');

            function performSearch() {
                const searchText = searchInput.value.toLowerCase();
                
                if (searchText) {
                    propositionCards.forEach(card => {
                        const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();
                        const cardDescription = card.querySelector('.card-description').textContent.toLowerCase();
                        const cardUser = card.querySelector('.card-user span').textContent.toLowerCase();
                        
                        if (cardTitle.includes(searchText) || 
                            cardDescription.includes(searchText) || 
                            cardUser.includes(searchText)) {
                            card.classList.remove('hidden');
                        } else {
                            card.classList.add('hidden');
                        }
                    });
                    
                    // Réinitialiser les filtres
                    filterButtons.forEach(b => b.classList.remove('active'));
                    filterButtons[0].classList.add('active'); // Activer "Tous"
                } else {
                    // Si la recherche est vide, afficher toutes les propositions
                    propositionCards.forEach(card => {
                        card.classList.remove('hidden');
                    });
                }
            }

            searchBtn.addEventListener('click', performSearch);

            // Permettre la recherche en appuyant sur Entrée
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        });


document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (navToggle && navMenu) {
      navToggle.addEventListener('click', function() {
        navToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
      });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (navToggle && navToggle.classList.contains('active')) {
          navToggle.classList.remove('active');
          navMenu.classList.remove('active');
        }
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 70,
            behavior: 'smooth'
          });
        }
      });
    });
    
    // Active navigation based on scroll position
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    function highlightNavLink() {
      const scrollPosition = window.scrollY + 100;
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
          navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + sectionId) {
              link.classList.add('active');
            }
          });
        }
      });
    }
    
    window.addEventListener('scroll', highlightNavLink);
    highlightNavLink();
    
    // Testimonial slider
    const testimonialTrack = document.querySelector('.testimonial-track');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const navDots = document.querySelectorAll('.nav-dot');
    const prevButton = document.querySelector('.nav-prev');
    const nextButton = document.querySelector('.nav-next');
    
    if (testimonialTrack && testimonialCards.length > 0) {
      let currentIndex = 0;
      
      function updateSlider() {
        testimonialTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        navDots.forEach((dot, index) => {
          dot.classList.toggle('active', index === currentIndex);
        });
      }
      
      if (prevButton) {
        prevButton.addEventListener('click', () => {
          currentIndex = (currentIndex - 1 + testimonialCards.length) % testimonialCards.length;
          updateSlider();
        });
      }
      
      if (nextButton) {
        nextButton.addEventListener('click', () => {
          currentIndex = (currentIndex + 1) % testimonialCards.length;
          updateSlider();
        });
      }
      
      navDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          currentIndex = index;
          updateSlider();
        });
      });
      
      // Auto slide every 5 seconds
      setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonialCards.length;
        updateSlider();
      }, 5000);
    }
    
    // Form submission
    const contactForm = document.querySelector('.contact-form');
    
    if (contactForm) {
      contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        
        // Here you would typically send the form data to a server
        // For this template, we'll just log it and show a success message
        console.log('Form submitted:', { name, email, subject, message });
        
        // Show success message
        const formGroups = contactForm.querySelectorAll('.form-group');
        formGroups.forEach(group => {
          group.style.display = 'none';
        });
        
        const submitButton = contactForm.querySelector('button[type="submit"]');
        submitButton.style.display = 'none';
        
        const successMessage = document.createElement('div');
        successMessage.className = 'success-message';
        successMessage.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-success); margin-bottom: 1rem;">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <h3>Message envoyé!</h3>
          <p>Merci de nous avoir contactés. Nous vous répondrons dans les plus brefs délais.</p>
          <button class="btn btn-primary" id="resetForm">Envoyer un autre message</button>
        `;
        
        contactForm.appendChild(successMessage);
        
        // Reset form button
        const resetButton = document.getElementById('resetForm');
        if (resetButton) {
          resetButton.addEventListener('click', function() {
            contactForm.reset();
            successMessage.remove();
            formGroups.forEach(group => {
              group.style.display = 'block';
            });
            submitButton.style.display = 'block';
          });
        }
      });
    }
    
    // Animate elements on scroll
    const animateElements = document.querySelectorAll('.service-card, .stats-card, .testimonial-card, .contact-form-container');
    
    function isElementInViewport(el) {
      const rect = el.getBoundingClientRect();
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    }
    
    function animateOnScroll() {
      animateElements.forEach(element => {
        if (isElementInViewport(element)) {
          element.classList.add('animate-in');
        }
      });
    }
    
    // Add initial classes
    animateElements.forEach(element => {
      element.classList.add('animate-item');
    });
    
    // Check on scroll
    window.addEventListener('scroll', animateOnScroll);
    
    // Check on initial load
    animateOnScroll();
    
    // Code window animation
    const codeWindow = document.querySelector('.code-window');
    if (codeWindow) {
      setTimeout(() => {
        codeWindow.classList.add('animate-in');
      }, 500);
    }
  });
  
  // Add these styles to your CSS for the animations
  document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
      .animate-item {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
      }
      
      .animate-in {
        opacity: 1;
        transform: translateY(0);
      }
      
      .code-window {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s ease, transform 0.8s ease;
      }
      
      .code-window.animate-in {
        opacity: 1;
        transform: translateY(0);
      }
      
      .success-message {
        text-align: center;
        padding: 2rem 0;
      }
    `;
    document.head.appendChild(style);
  });
  