import React, { useEffect, useState } from 'react';

function ViewResult({ view_id, display_id, api_url }) {
  const [results, setResults] = useState([]);

  useEffect(() => {
    async function fetchResults() {
      const res = await fetch(api_url)
      const json = await res.json();
      setResults(json.data);
    }
    fetchResults();
  }, [api_url]);

  return (
    <div>
      <h1>{view_id} {display_id}</h1>
      <p>Fetching from {api_url}</p>
      <pre><code>{JSON.stringify(results, null, 4)}</code></pre>
    </div>
  )
}

function Entity({ api_url }) {
  const [results, setResults] = useState([]);

  useEffect(() => {
    async function fetchResults() {
      const res = await fetch(api_url)
      const json = await res.json();
      setResults(json);
    }
    fetchResults();
  }, [api_url]);

  return (
    <div>
      <p>Fetching from {api_url}</p>
      <pre><code>{JSON.stringify(results, null, 4)}</code></pre>
    </div>
  )
}

function Recipe({ api_url }) {
  const [results, setResults] = useState([]);

  useEffect(() => {
    async function fetchResults() {
      const res = await fetch(`${api_url}?include=field_tags,field_media_image.field_media_image`)
      const json = await res.json();
      setResults(json);
    }
    fetchResults();
  }, [api_url]);

  return (
    <div>
      <p>Recipe from {api_url}</p>
      <pre><code>{JSON.stringify(results, null, 4)}</code></pre>
    </div>
  )
}

function RouteComponentFactory(props) {
  const [page, setPage] = useState(null);
  // Debugger for folks :)
  console.log(props);

  const { pathname } = props.location;
  useEffect(() => {
    async function decoupledRouter() {
      const decoupledRouterRequest = await fetch(`${process.env.REACT_APP_API_URL}/router/translate-path?path=${pathname}&_format=json`);
      const routerData = await decoupledRouterRequest.json();
      setPage(routerData);
    }
    decoupledRouter();
  }, [pathname]);

  if (page === null) {
    // Put a spinner.
    return null;
  }

  if (page.hasOwnProperty('details') && page.details === "None of the available methods were able to find a match for this path.") {
    return <p>Not found</p>
  }
  if (page.hasOwnProperty('views')) {
    return <ViewResult api_url={page.jsonapi.url} display_id={page.views.display_id} view_id={page.views.view_id} />
  }
  if (page.hasOwnProperty('jsonapi')) {
    return page.jsonapi.resourceName === 'node--recipe' ?
      <Recipe api_url={page.jsonapi.individual} /> :
      <Entity api_url={page.jsonapi.individual} />
  }

  return <p>Not found :(</p>
}
export default RouteComponentFactory
