function init()
{
	//message d'alerte si le contenu entré par l'utilisateur ne répond pas aux conditions.
	function validNewTaskInput(e)
	{
		e.preventDefault();
		let newTacheId = document.getElementById("newTache");
		if (newTacheId.value.length >= 5 && newTacheId.value.length <= 50)
		{
			document.ajouterTache.submit();
		}
		else
		{
			let smsAlert = document.getElementById("smsAlert");
			if (smsAlert)
			{
				smsAlert.remove();
			}
			let parent = document.getElementById("ajouterTache");
			let balise = document.createElement("p");
			let contenu = document.createTextNode("Votre tâche doit comprendre entre 5 et 50 caractères");
			balise.id = "smsAlert";
			balise.appendChild(contenu);
			parent.appendChild(balise);

		}
	}
	//le formulaire peut être validé avec la touche "enter".
	function submitWithEnter(e)
	{
	    if(e.keyCode == 13)
	    {
	    	validNewTaskInput(e);
	    }
	}
	//enregistrement de l'interface après avoir bougé l'ordre des choses à faire.
	function recordUI()
	{
		let aFaireListClass = document.querySelectorAll(".aFaireList");
		let aFaireListLength = aFaireListClass.length;
		let uiArray = "";
		for (let i = 0; i < aFaireListLength; i++)
		{
			let afaireElement = aFaireListClass[i].querySelector("input").value;
			uiArray += afaireElement;
			if (i < aFaireListLength - 1)
			{
				uiArray += ',';
			}
		}
		let form = document.createElement("form");
		let input = document.createElement("input");
		let inputContent = document.createTextNode(uiArray);
		
		form.setAttribute('action', './formulaire.php');
		form.setAttribute('method', 'post');
		form.setAttribute('style', 'display: none;');
		input.setAttribute('name', 'ui');
		input.setAttribute('value', uiArray);

		form.appendChild(input);
		document.body.appendChild(form);
		form.submit();
	}

	function launchDrag(e)
	{
		e.preventDefault();
		let elementsListParent = document.getElementById("aFaireListParent");
		let elementsList = elementsListParent.children;
		let elementsListLength = elementsList.length;

		let elementDrag = this;

		let elementsListOriginOrder = document.querySelectorAll(".aFaireList");

		let elementNodeIndex = Array.prototype.indexOf.call(elementsList, elementDrag);

		elementDrag.classList.add("aFaireListDrag");

		if (elementDrag.querySelector("input").checked == false)
		{
			elementDrag.querySelector("input").checked = true;
		}
		else
		{
			elementDrag.querySelector("input").checked = false;
		}

		function dragElement(event)
		{
			let elementDragPosY = elementDrag.offsetTop;
			let elementDragHeight = elementDrag.offsetHeight;

			let elementPrevious = elementDrag.previousElementSibling; 
			let elementNext = elementDrag.nextElementSibling;

			//deplacer l'élément séléctionné dans le DOM (vers le haut).
			if (event.clientY + document.documentElement.scrollTop < elementDragPosY && elementPrevious || event.touches != undefined && event.touches[0].clientY + document.documentElement.scrollTop < elementDragPosY && elementPrevious)
			{
				elementPrevious.before(elementDrag);
				//scroll auto pdt le drag.
				window.scrollTo(0, event.clientY - 1); 
				if (event.touches != undefined)
				{
					window.scrollTo(0, event.touches[0].clientY - 1); 
				}
			}
			//deplacer l'élément séléctionné dans le DOM (vers le bas).
			if (event.clientY + document.documentElement.scrollTop > elementDragPosY + elementDragHeight && elementNext || event.touches != undefined && event.touches[0].clientY + document.documentElement.scrollTop > elementDragPosY + elementDragHeight && elementNext)
			{
				elementNext.after(elementDrag);
				//scroll auto pdt le drag.
				window.scrollTo(0, event.clientY + 1); 
				if (event.touches != undefined)
				{
					window.scrollTo(0, event.touches[0].clientY + 1);
				}
			}
		}
		function dropElement()
		{	
			for (let i = 0; i < elementsListLength; i++)
			{
				//les éléments de la liste des choses à faire ont-ils changé d'ordre ?
				if (elementsListOriginOrder[i].querySelector("input").value != elementsList[i].querySelector("input").value)
				{
					//si oui on enregistre l'inferface.
					recordUI();
				}
			}
			elementDrag.classList.remove("aFaireListDrag");
			//désactiver les sous-events liés au drag and drop.
			document.onmousemove = null;
			document.ontouchmove = null;
			document.onmouseup = null;
			document.ontouchend = null;
		};
		//sous-events liés au drag and drop.
		document.ontouchmove = function(event)
		{
			dragElement(event);
		};
		document.onmousemove = function(event)
		{
			dragElement(event);
		};

		document.onmouseup = function()
		{
			dropElement();
		};
		document.ontouchend = function()
		{
			dropElement();
		};
	}

	function initDragAndDrop()
	{
		let elementsListParent = document.getElementById("aFaireListParent");
		let elementsList = elementsListParent.children;
		let elementsListLength = elementsList.length;
		for (let i = 0; i < elementsListLength; i++)
		{
			if (touchDevice == false)
			{
				elementsList[i].addEventListener("mousedown", launchDrag, false);
			}
			else
			{
				elementsList[i].addEventListener("touchstart", launchDrag, {passive: false});
			}
		}
	}
	//les fonctions tactiles sont-elles activées sur le navigateur?
	function isTouchDevice()
	{
  		return !!('ontouchstart' in window) // Autres browsers
  		|| !!('onmsgesturechange' in window); // IE10
	};
	//event de validation du formulaire (enter ou clique gauche sur le bouton "ajouter").
	document.addEventListener("keydown", submitWithEnter, false);
	document.getElementById("submitNewTask").addEventListener("click", validNewTaskInput, false);

	let touchDevice = isTouchDevice();
	initDragAndDrop();
}
window.onload = init;