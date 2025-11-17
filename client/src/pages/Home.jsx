import React, { useEffect, useState } from 'react'
import axios from 'axios'

export default function Home(){
  const [menu, setMenu] = useState([])
  const [loading, setLoading] = useState(true)
  useEffect(()=>{
    axios.get('/api/menu').then(r=>{
      setMenu(r.data.data || [])
    }).catch(()=>{
      setMenu([])
    }).finally(()=>setLoading(false))
  },[])

  if (loading) return <div>Loading...</div>
  return (
    <div>
      <h1>Menu</h1>
      {menu.length === 0 && <p>No menu items found.</p>}
      <ul>
        {menu.map(item=> (
          <li key={item.id}>
            <strong>{item.name}</strong> — {item.description} — {item.price}
          </li>
        ))}
      </ul>
    </div>
  )
}
