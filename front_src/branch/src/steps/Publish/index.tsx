import { useUnit } from "effector-react"
import FirstLastPost from "./FirstLastPost"
import Indicator from "./Indicator"
import { $allRight } from "store"
import { useEffect } from "react"
import { componentAdded } from "reused/Dialog/store"
import Dialog from "./Dialog"

const Publish = () => {
  const allRight = useUnit($allRight)

  useEffect(() => {
    if (allRight) {
      componentAdded(<Dialog />)
    }
  }, [allRight])

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <Indicator />
      <FirstLastPost />
    </div>
  )
}

export default Publish
