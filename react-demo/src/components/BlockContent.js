import React, { useEffect, useState } from 'react'

export default function BlockContent({ bundle, uuid }) {
  const [results, setResults] = useState(null);

  useEffect(() => {
    async function fetchResults() {
      const res = await fetch(`${process.env.REACT_APP_API_URL}/jsonapi/block_content/${bundle}/${uuid}`)
      const json = await res.json();
      setResults(json);
    }
    fetchResults();
  }, [bundle, uuid]);

  if (!results) {
    return null;
  }

  return (
    <div>
      <h2>{results.data.attributes.field_title}</h2>
      <p>{results.data.attributes.field_summary}</p>
    </div>
  )
}
