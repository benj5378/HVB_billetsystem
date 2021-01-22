function view(pageId, group) {
	//if (document.getElementByClass("page").id
	var elements = document.getElementsByClassName(group);
	for (var i = 0; i < elements.length; i++) {
		elements[i].id === pageId ? elements[i].style.display = "block" : elements[i].style.display = "none";
	}
}