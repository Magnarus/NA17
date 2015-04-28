
var generateNewContenu = function(typeDef, titre, contenu) {
	var self = this

	// Main
	this.node = document.createElement("div")

	// Boutons
	div_btn = document.createElement("div")
	div_btn.className = "right"

	bt_up = document.createElement("input")
	bt_up.type = "button"
	bt_up.value = "▲"
	bt_up.className = "btn btn-default"
	div_btn.appendChild(bt_up)
	bt_down = document.createElement("input")
	bt_down.type = "button"
	bt_down.value = "▼"
	bt_down.className = "btn btn-default"
	div_btn.appendChild(bt_down)
	bt_remove = document.createElement("input")
	bt_remove.type = "button"
	bt_remove.value = "-"
	bt_remove.className = "btn btn-default"
	div_btn.appendChild(bt_remove)
	bt_add = document.createElement("input")
	bt_add.type = "button"
	bt_add.value = "+"
	bt_add.className = "btn btn-default"
	div_btn.appendChild(bt_add)
	
	this.node.appendChild(div_btn)

	// Titre
	div_title = document.createElement("div")
	div_title.className = "input-group"
	labelTitle = document.createElement("span")
	labelTitle.innerHTML = "Titre :"
	labelTitle.className = "input-group-addon"
	div_title.appendChild(labelTitle)

	title = document.createElement("input")
	title.name="contenuTitre[]"
	title.className="form-control"
	div_title.appendChild(title)
	if(titre !== undefined)
	{
		title.value = titre
	}
	this.node.appendChild(div_title)

	// Type
	ident = (new Date()).getTime() * Math.random()

	div_type = document.createElement("div")
	div_type.className = "input-group"

	labelRadioType1 = document.createElement("label")
	self.radioType1 = document.createElement("input")
	self.radioType1.type = "radio"
	self.radioType1.name = ident
	self.radioType1.value = "Texte"
	labelRadioType1.appendChild(self.radioType1)
	labelRadioType1.appendChild(document.createTextNode(" Texte "))
	div_type.appendChild(labelRadioType1)

	labelRadioType2 = document.createElement("label")
	self.radioType2 = document.createElement("input")
	self.radioType2.type = "radio"
	self.radioType2.name = ident
	self.radioType2.value = "Image"
	labelRadioType2.appendChild(self.radioType2)
	labelRadioType2.appendChild(document.createTextNode(" Image "))
	div_type.appendChild(labelRadioType2)

	self.type = document.createElement("input")
	self.type.type="hidden"
	self.type.name="contenuType[]"
	self.type.value=""
	div_title.appendChild(self.type)

	this.node.appendChild(div_type)

	// Contenu
	this.divContenu = document.createElement("div")
	this.node.appendChild(this.divContenu)

	// Actions
	
	// Suppression
	bt_remove.onclick = function(e) {
		self.node.parentNode.removeChild(self.node)
	}

	// Ajout
	bt_add.onclick = function(e) {
		addContenuText(this.parentNode.parentNode)
	}

	// Up
	bt_up.onclick = function(e) {
		cont = self.node.parentNode
		if(self.node != cont.firstChild)
		{
			previous = self.node.previousSibling
			cont.removeChild(self.node)
			cont.insertBefore(self.node, previous)
		}
	}
	// Down
	bt_down.onclick = function(e) {
		cont = self.node.parentNode
		if(self.node != cont.lastChild)
		{
			next = self.node.nextSibling.nextSibling
			cont.removeChild(self.node)
			cont.insertBefore(self.node, next)
		}
	}

	// Texte
	self.radioType1.onclick = function(e) {
		self.defineText()
	}
	// Image
	self.radioType2.onclick = function(e) {
		self.defineImage()
	}

	this.defineText = function(contenu) {
		if(self.type.value != "texte")
		{
			self.type.value = "texte"
			self.radioType1.checked = "checked"
			self.radioType2.checked = ""
			self.divContenu.innerHTML = ''

			// Textarea
			text = document.createElement("textarea")
			text.name="contenuValeur[]"
			text.className = "form-control"
			text.placeholder = "Contenu du paragraphe"
			if(contenu !== undefined)
			{
				text.innerHTML = contenu;
			}
			self.divContenu.appendChild(text)
		}
	}

	this.defineImage = function(contenu) {

		if(self.type.value != "image")
		{
			self.type.value = "image"
			self.radioType1.checked = ""
			self.radioType2.checked = "checked"
			self.divContenu.innerHTML = ''

			// Image
			
			div_img = document.createElement("div")
			div_img.className = "input-group" 
			 
			labelImage = document.createElement("span")
			labelImage.className = "input-group-addon"
			labelImage.innerHTML = "Adresse URL : "
			div_img.appendChild(labelImage)

			image = document.createElement("input")
			image.name="contenuValeur[]"
			image.className="col50"
			image.placeholder="http://"
			image.className = "form-control"
			div_img.appendChild(image)
			self.divContenu.appendChild(div_img)

			imageDisplay = document.createElement("img")
			self.divContenu.appendChild(imageDisplay)

			if(contenu !== undefined)
			{
				image.value = contenu;
				imageDisplay.src = contenu;
			}


			// Action
			image.onchange = function(e) {
				imageDisplay.src = this.value
			}
		}
	}

	if(typeDef == "image")
		this.defineImage(contenu)
	else
		this.defineText(contenu)

	this.getNode = function() {
		return this.node
	}
}


function addContenuText(pos, type, titre, contenu) {

	obj = new generateNewContenu(type, titre, contenu)
	cont = document.getElementById('contenuContainer')

	if(pos == -1)
	{
		cont.insertBefore(obj.getNode(), cont.firstChild)
	}
	else if(typeof pos === 'object' && pos != cont.lastChild)
	{
		cont.insertBefore(obj.getNode(), pos.nextSibling)
	}
	else
	{
		cont.appendChild(obj.getNode())
	}

}
