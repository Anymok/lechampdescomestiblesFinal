import React, { useState } from "react";
import ReactDOM from 'react-dom'
import {StrictMode} from 'react';
import {render} from 'react-dom'
import { usePAginatedFetch } from './hooks'
import { useEffect } from 'react'

import { createRoot } from 'react-dom/client';

// function pour obtenir les get de l'url
function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : "";	
	}
	return vars;
}


var ref = $_GET('ref');
var title = $_GET('title');
var color = $_GET('color');
var type = $_GET('type');

var string = "/api/plants?page=1&itemsPerPage=6&id="+ref+"&title="+title+"&color="+color+"&type="+type+""


function Plants() {

    const {items: Plants, Suivant, Precedent, loading, hasMore, hasLess } = usePAginatedFetch(string)

    useEffect(() => {
        Suivant(),
        Precedent() 
        }, [])

    return <div className="row">
            {Plants.map(p => <Plant key={p.id} Plants={p} />)}
            <div class="row justify-content-md-center p-compo3 p-3">
                <div className="col-md-5 justify-content-md-start">
                    {hasLess && <button disabled={loading} className="btn btn-primary" onClick={Precedent}>Page précédente</button>}
                </div>
                <div className="col-md-6 text-end">
                    {hasMore && <button disabled={loading} className="btn btn-primary" onClick={Suivant}>Page suivante</button>}
                </div>
                </div>
            </div>

        
}

const Plant = React.memo(({Plants}) => {
    return <div className="col-md-4">
                <div className="card mb-4 plants-collection">
                    {Plants.pictures[0] && <img src={ "/images/plants/" + Plants.pictures[0].filename }  alt="card-img-top" style={{ width: '100%', height: undefined, aspectRatio: 360 / 230, }}/>}
                    {Plants.pictures == "" && <img src="/images/plants/empty.jpg"   alt="card-img-top" style={{ width: '100%', height: undefined, aspectRatio: 360 / 230, }}/>}
                <div className="card-body">
                    <h5 className="card-tittle"><a href={'catalogue/' + Plants.slug + "-" + Plants.id} >{ Plants.title }</a></h5>  
                    <p className="card-text">{ Plants.color } ({ Plants.type })</p>
                <div className="text-primary"style={{ fontSize:'2rem', fontWeight: 'bold'}} >{ Plants.price } €</div>
                </div>
                </div>
            </div>
          
})

 
const containerCatag = document.getElementById('catalogue');
const rootCatag = createRoot(containerCatag);

rootCatag.render(
  <React.StrictMode>
    <Plants/>
  </React.StrictMode>);


 /*class SearchBar extends React.Component {

    constructor (props) {
        super(props)
        this.state = {
            filterRef: "",
            filterTitle: "",
            filterColor: "",
            filterType: "",
            filtrePrice: null
        }
        this.handleFilterRefChange = this.handleFilterRefChange.bind(this)
        this.handleFilterTitleChange = this.handleFilterTitleChange.bind(this)
        this.handleFilterColorChange = this.handleFilterColorChange.bind(this)
        this.handleFilterTypeChange = this.handleFilterTypeChange.bind(this)
        this.handleFilterPriceChange = this.handleFilterPriceChange.bind(this)
    }

    stringEvent() {
        string = "/api/plants?page=1&itemsPerPage=6&id="+this.state.filterRef+"&title="+this.state.filterTitle+"&color="+this.state.filterColor+"&type="+this.state.filterType+""  
    }

    handleFilterRefChange (filterRef) {
        this.setState({filterRef})
        this.stringEvent()
    }

    handleFilterTitleChange (filterTitle) {
        this.setState({filterTitle})
        this.stringEvent()
    }

    handleFilterColorChange (filterColor) {
        this.setState({filterColor})
        this.stringEvent()
    }

    handleFilterTypeChange (filterType) {
        this.setState({filterType})
        this.stringEvent()
    }

    handleFilterPriceChange (filtrePrice) {
        this.setState({filtrePrice})
        this.stringEvent()
    }

     render () {
        return  <React.StrictMode>
            {JSON.stringify(this.state)}
        <Search
        filterRef={this.state.filterRef}
         filterTitle={this.state.filterTitle}
         filterColor={this.state.filterColor}
         filterType={this.state.filterType}
         filtrePrice={this.state.filtrePrice}
         OnFilterRefChange={this.handleFilterRefChange}
         OnFilterTitleChange={this.handleFilterTitleChange}
         onFilterColorChange={this.handleFilterColorChange}
         onFilterTypeChange={this.handleFilterTypeChange}
         onFilterPriceChange={this.handleFilterPriceChange}
         />
    </React.StrictMode>
     }
 }


 class Search extends React.Component {


    constructor (props) {
        super(props)
        this.handleFilterRefChange = this.handleFilterRefChange.bind(this)
        this.handleFilterTitleChange = this.handleFilterTitleChange.bind(this)
        this.handleFilterColorChange = this.handleFilterColorChange.bind(this)
        this.handleFilterTypeChange = this.handleFilterTypeChange.bind(this)
        this.handleFilterPriceChange = this.handleFilterPriceChange.bind(this)
    }

    handleFilterRefChange(e) {
        this.props.OnFilterRefChange(e.target.value)
    }

    handleFilterTitleChange(e) {
        this.props.OnFilterTitleChange(e.target.value)
    }
    

    handleFilterColorChange(e) {
        this.props.onFilterColorChange(e.target.value)
    }

    handleFilterTypeChange(e) {
        this.props.onFilterTypeChange(e.target.value)
    }
    
    handleFilterPriceChange(e) {
        this.props.onFilterPriceChange(e.target.value)
    }

    render () {
        const {filterRef, filterTitle, filterColor, filterType, filtrePrice} = this.props
    return <div className="row align-items-end form-group">
                <div className="col form-floating">
                    <input type="text" id="ref" value={filterRef} name="ref" placeholder="Référence" className="form-control" onChange={this.handleFilterRefChange}/>
                    <label for="ref">Référence</label>
                    <p>{string}</p>
                </div>
                <div className="col form-floating">
                    <input type="text" id="title" value={filterTitle} name="title" placeholder="Titre" className="form-control" onChange={this.handleFilterTitleChange}/>
                    <label for="title">Titre</label>
                </div>
                <div className="col form-floating">
                    <input type="text" id="color" value={filterColor} name="color" placeholder="Couleur" className="form-control" onChange={this.handleFilterColorChange}/>
                    <label for="color">Couleur</label>
                </div>
                <div className="col form-floating">
                    <input type="text" id="type" value={filterType} name="type" placeholder="Type" className="form-control" onChange={this.handleFilterTypeChange}/>
                    <label for="type">Type</label>
                </div>
                <div className="col form-floating">
                    <input type="number" id="maxPrice" value={filtrePrice} name="maxPrice" placeholder="Prix maximal" className="form-control" onChange={this.handleFilterPriceChange}/>
                    <label for="maxPrice">Prix maximal</label>
                </div>
            </div>
    }
 }

  
  const containerSearch = document.getElementById('search');
  const rootSearch = createRoot(containerSearch);

  rootSearch.render(
    <React.StrictMode>
      
    </React.StrictMode>);
*/



      
