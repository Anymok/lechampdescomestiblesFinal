import { useState } from "react"
import { useCallback } from "react"

export function usePAginatedFetch (url) {
    const [loading, setLoading] = useState(false)
    const [items, setItems] = useState([])
    const [previous, setPrevious] = useState(null)
    const Precedent = useCallback(async () => {
        setLoading(true)
        const response = await fetch(previous || url, {
            headers: {
                'Accept' : 'application/ld+json'
            }
        })

        const responseData = await response.json()
        if (response.ok) { 
            setItems(responseData['hydra:member'])
            if (responseData['hydra:view'] && responseData['hydra:view']['hydra:previous']) 
            {
                setPrevious(responseData['hydra:view']['hydra:previous'])
                if (responseData['hydra:view'] && responseData['hydra:view']['hydra:next']) 
                {
                    setNext(responseData['hydra:view']['hydra:next'])
                } else {
                    setNext(null)
                }
            } else {
                setPrevious(null)
                if (responseData['hydra:view'] && responseData['hydra:view']['hydra:next']) 
                {
                    setNext(responseData['hydra:view']['hydra:next'])
                } else {
                    setNext(null)
                }
            }
        } else {
            console.error(responseData)
        }
        setLoading(false)
    }, [url, previous])
    const [next, setNext] = useState(null)
    const Suivant = useCallback(async () => {
        setLoading(true)
        const response = await fetch(next || url, {
            headers: {
                'Accept' : 'application/ld+json'
            }
        })

        const responseData = await response.json()
        if (response.ok) { 
            setItems(responseData['hydra:member'])
            if (responseData['hydra:view'] && responseData['hydra:view']['hydra:next']) 
            {
                setNext(responseData['hydra:view']['hydra:next'])
                if (responseData['hydra:view'] && responseData['hydra:view']['hydra:previous']) 
                {
                    setPrevious(responseData['hydra:view']['hydra:previous'])
                } else {
                    setPrevious(null)
                }
            } else {
                setNext(null)
                if (responseData['hydra:view'] && responseData['hydra:view']['hydra:previous']) 
                {
                    setPrevious(responseData['hydra:view']['hydra:previous'])
                } else {
                    setPrevious(null)
                }
            }
        } else {
            console.error(responseData)
        }
        setLoading(false)
    }, [url, next])

    return {
        items,
        Suivant,
        Precedent,
        loading,
        hasMore: next != null,
        hasLess: previous != null
    }

}
