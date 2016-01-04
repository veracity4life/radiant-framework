

var Pagination = {

	table : {
		cssId: "",
		element: undefined,
		rowCount: 0,
		page: 1,
		pageSize: 20,
		totalPages: 1,
		rowElements: document.createElement("tbody")
	},

	controls : {
		controlBox : document.getElementById("pagination"),
		prevObject : undefined,
		nextObject : undefined
	},

	init : function(config) {
		var t = this.table,
			r = [],
			l = 0;

		t.cssId = config.cssId;
		t.pageSize = config.pageSize;

		t.element = document.getElementById(t.cssId);
		t.rowCount = t.element.getElementsByTagName('tr').length;
		t.totalPages = Math.ceil(t.rowCount / t.pageSize);

/*		r = t.element.getElementsByTagName('tr')
		l = r.length;

		for (var i = l - 1; i >= 1; i -= 1) {
			t.rowElements.appendChild(r[1]);
		}
*/
		this.createCache();

		this.createControls();

	},

	createControls : function() {
		var c = this.controls;

		var prev = document.createElement("div");
			prev.className = "left";
		var prevLink = document.createElement("a");
			prevLink.addEventListener("click", Pagination.prevPage);
			prevLink.innerHTML = "Prev Page";
		prev.appendChild(prevLink);
		c.prevObject = prev;

		var next = document.createElement("div");
			next.className = "right";
		var nextLink = document.createElement("a");
			nextLink.addEventListener("click", Pagination.nextPage);
			nextLink.innerHTML = "Next Page";
		next.appendChild(nextLink);
		c.nextObject = next;

		c.controlBox.appendChild(prev);
		c.controlBox.appendChild(next);

		c.prevObject.style.display = "none";

		if(this.totalPages < 2) {
			c.nextObject.style.display = "none";
		}
	},

	createCache : function() {
		var t = Pagination.table,
			c = Pagination.controls,
			cache = [];



	},

	nextPage : function() {
		var t = Pagination.table,
			c = Pagination.controls;

		if(t.page < t.totalPages) {
			t.page += 1;
			if(t.page >= t.totalPages){
				c.nextObject.style.display = "none";
			}

			if(c.prevObject.style.display == "none") {
				c.prevObject.style.display = "inherit";
			}

		} else {
			alert("You are currently on the last page of available data.");
			return;
		}

		Pagination.renderPage();

	},

	prevPage : function() {
		var t = Pagination.table,
			c = Pagination.controls;

		if(t.page > 1) {
			t.page -= 1;

			if(t.page < 2){
				c.prevObject.style.display = "none";
			}

			if(c.nextObject.style.display == "none") {
				c.nextObject.style.display = "inherit";
			}

		} else {
			alert("You are currently on the first page of available data.");
			return;
		}

		Pagination.renderPage();
	},

	renderPage : function() {
		var t = Pagination.table,
			max = t.page * t.pageSize,
			min = max - t.pageSize,
			tableBody = t.element.getElementsByTagName("tbody")[0];


		tableBody.innerHTML = "";

		for (var i = min; i < max; i+=1) {
			tableBody.appendChild(t.rowElements.children[i]);
		};

		console.log("max = " + max);
		console.log("min = " + min);

	}



}




