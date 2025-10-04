type Props = {
  title?: string | number;
  children?: React.ReactNode;
}

const Wrapper = ({ title, children }: Props) => {
  return (
    <div className="flex flex-row justify-center">
      <div className="w-full md:w-2xl lg:w-4xl bg-base-100 p-4">
        {!title ? null : (
          <h1 className="text-2xl mt-2 mb-3">
            {title}
          </h1>
        )}
        {children}
      </div>
    </div>
  )
}

export default Wrapper
